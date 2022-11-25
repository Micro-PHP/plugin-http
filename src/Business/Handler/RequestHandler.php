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
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
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
        } catch (HttpException $e) {
            $response = $this->createExceptionResponse($request, $e);
            $exception = $e;
        } catch (\Throwable $internalException) {
            $exception = $internalException;
            $response = $this->createExceptionResponse($request, $exception);
            $this->httpLogger->error($exception, $request, $response, $exception->getTrace());
        }

        if(!$route) {
            $response->send();
            $this->httpLogger->access($request, $response);

            return;
        }

        $context = new ResponseHandlerContext($request, $response, $exception);
        foreach ($this->handlerExtractor->extractResponseHandlers($route) as $handler) {
            try {
                $handler->handle($context);
            } catch (\Throwable $e) {
                $context->setException($e);
            }
        }

        $exceptionResp = $context->getException();
        $response = $context->getResponse();
        if($exceptionResp) {
            $response = $this->createExceptionResponse($request, $exceptionResp, $response);
            $this->httpLogger->warning($request, $response, $exceptionResp->getMessage(), $exceptionResp->getTrace());
        }

        if(!($response instanceof Response)) {
            //$this->httpLogger->error($exception, $request, $response, $exception?->getTrace());
            throw new \RuntimeException(sprintf(
                'Response should be %s. %s given', Response::class, gettype($response)));
        }

        $response->send();
        $this->httpLogger->access($request, $response);

        if($exception) {
            throw $exception;
        }
    }

    protected function createExceptionResponse(Request $request, \Throwable $throwable, Response $response = null): Response
    {
        $status = 500;
        $message = 'Internal sever error.';

        if($throwable instanceof HttpException) {
            $message = $throwable->getMessage();
            $status = $throwable->getCode();
        }

        if($response === null) {
            $response = new Response();
        }

        $response->setStatusCode($status);
        $response->setContent($message);

        return $response;
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
            throw new HttpNotFoundException('', $exception);
        } catch (MethodNotAllowedException $exception) {
            throw new \Micro\Plugin\Http\Exception\MethodNotAllowedException('', $exception);
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

        } catch (HttpException $exception) {
            $this->httpLogger->warning($request, null, $exception->getMessage());

            throw $exception;
        } catch (ResourceNotFoundException $exception) {
            $this->httpLogger->warning($request, null, $exception->getMessage());

            throw new HttpNotFoundException();
        }

        $this->containerRegistry?->register(Request::class, function () use ($request): Request {
            return $request;
        });

        $routeOptions = $route->getOption('options');

        $controller = $routeOptions['controller'] ?? null;

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

        $action = $routeOptions['action'] ?? $routeOptions['route_name'];

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