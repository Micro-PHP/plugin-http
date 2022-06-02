<?php

namespace Micro\Plugin\Http\Business\Handler;


use Micro\Component\DependencyInjection\Autowire\AutowireHelperInterface;
use Micro\Component\DependencyInjection\ContainerRegistryInterface;
use Micro\Plugin\Http\Business\Logger\HttpLoggerInterface;
use Micro\Plugin\Http\Business\Matcher\UrlMatcherInterface;
use Micro\Plugin\Http\Business\RouteHandler\Extractor\RouteHandlerExtractorInterface;
use Micro\Plugin\Http\Exception\HttpException;
use Micro\Plugin\Http\Exception\HttpNotFoundException;
use Micro\Plugin\Http\Handler\Request\RequestHandlerContext;
use Micro\Plugin\Http\Handler\Response\ResponseHandlerContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Route;
use Throwable;

class RequestHandler implements RequestHandlerInterface
{
    /**
     * @param UrlMatcherInterface $urlMatcher
     * @param AutowireHelperInterface $autowireHelperFactory
     * @param RouteHandlerExtractorInterface $handlerExtractor
     * @param HttpLoggerInterface $httpLogger
     * @param ContainerRegistryInterface|null $containerRegistry
     */
    public function __construct(
        private readonly UrlMatcherInterface $urlMatcher,
        private readonly AutowireHelperInterface $autowireHelperFactory,
        private readonly RouteHandlerExtractorInterface $handlerExtractor,
        private readonly HttpLoggerInterface $httpLogger,
        private readonly ?ContainerRegistryInterface $containerRegistry = null
    )
    {
    }

    /**
     * @TODO: Action !!!!
     *
     * {@inheritDoc}
     */
    public function handleRequest(Request $request): void
    {
        $exception = null;
        $route = null;
        try {
            $route = $this->matchRoute($request);
            $response = $this->handle($route, $request);

        //    $this->httpLogger->access($request, $response);
        } catch (HttpException $exception) {
          //  $response = $this->createExceptionResponse($request, $exception);

           // $this->httpLogger->access($request, $response);
        } catch (\Throwable $exception) {
            $response = $this->createExceptionResponse($request, $exception);

           // $this->httpLogger->error($exception, $request, $response, $exception->getTrace());
           // $this->httpLogger->access($request, $response);

            throw $exception;
        } finally {
            if(!$route) {
                return;
            }

            $context = new ResponseHandlerContext($request, $response, $exception);
            foreach ($this->handlerExtractor->extractResponseHandlers($route) as $handler) {
                $handler->handle($context);
            }

            $response = $context->getResponse();

            if(!($response instanceof Response)) {
                throw new \RuntimeException(sprintf(
                    'Response should be %s. %s given', Response::class, gettype($response)));
            }

            $response->send();
        }
    }

    protected function createExceptionResponse(Request $request, \Throwable $throwable): Response
    {
        $status = 500;
        $message = 'Internal sever error.';

        if($throwable instanceof HttpException) {
            $message = $throwable->getMessage();
            $status = $throwable->getCode();
        }

        return new Response($message, $status);
    }

    /**
     * @param Request $request
     * @return Route
     */
    protected function matchRoute(Request $request): Route
    {
        try {
            return $this->urlMatcher->match($request);
        } catch (ResourceNotFoundException $exception) {
            throw new HttpNotFoundException('');
        }
    }

    /**
     * @param Route $route
     * @param Request $request
     *
     * @return mixed
     *
     * @throws HttpException
     * @throws Throwable
     */
    protected function handle(Route $route, Request $request): mixed
    {
        try {
            foreach ($this->handlerExtractor->extractRequestHandlers($route) as $handler) {
                $handlerContext = new RequestHandlerContext($request);
                $handler->handle($handlerContext);
            }

        } catch (ResourceNotFoundException $exception) {
            $this->httpLogger->warning($request, null, $exception->getMessage());

            throw new HttpNotFoundException();
        }

        $this->containerRegistry?->register(Request::class, function () use ($request): Request {
            return $request;
        });

        $controller = $route->getOption('controller');

        if(!$controller) {
            $this->httpLogger->warning(
                $request,
                null,
                sprintf('Controller is not declared for the route "%s".', $request->getPathInfo())
            );

            throw new HttpNotFoundException();
        }

        if(!class_exists($controller)) {
            $this->httpLogger->warning($request, null, sprintf('Controller class "%s" does not exists.', $controller));

            throw new HttpNotFoundException();
        }

        $controllerObjectCallback = $this->autowireHelperFactory->autowire($controller);
        $controllerObject = $controllerObjectCallback();

        $action = $route->getOption('action') ?? '';

        if(!method_exists($controllerObject, $action)) {
            $this->httpLogger->warning($request, null, sprintf('The method "%s()" in the controller "%s" is not declared.', $action, $controller));

            throw new HttpNotFoundException();
        }

        /** @var Response $response */
        $responseCallback = $this->autowireHelperFactory->autowire([$controllerObject, $action]);
        /** @var Response $response */
        $response = $responseCallback();

        return $response;
    }
}