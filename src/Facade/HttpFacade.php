<?php

namespace Micro\Plugin\Http\Facade;

use Micro\Component\DependencyInjection\ContainerRegistryInterface;
use Micro\Plugin\Http\Business\Handler\RequestHandlerFactoryInterface;
use Micro\Plugin\Http\Business\Matcher\UrlMatcherFactoryInterface;
use Micro\Plugin\Http\Business\Request\RequestBuilderFactoryInterface;
use Micro\Plugin\Http\Business\Request\RequestBuilderInterface;
use Micro\Plugin\Http\Business\UrlGenerator\UrlGeneratorFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HttpFacade implements HttpFacadeInterface
{
    /**
     * @param UrlMatcherFactoryInterface $matcherFactory
     * @param RequestHandlerFactoryInterface $requestHandlerFactory
     * @param RequestBuilderFactoryInterface $requestBuilderFactory
     * @param UrlGeneratorFactoryInterface $urlGeneratorFactory
     */
    public function __construct(
        private readonly UrlMatcherFactoryInterface $matcherFactory,
        private readonly RequestHandlerFactoryInterface $requestHandlerFactory,
        private readonly RequestBuilderFactoryInterface $requestBuilderFactory,
        private readonly UrlGeneratorFactoryInterface $urlGeneratorFactory,
        private readonly ContainerRegistryInterface $containerRegistry
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function match(Request $request)
    {
        return $this->matcherFactory->create()->match($request);
    }

    /**
     * {@inheritDoc}
     */
    public function handleRequest(Request $request): void
    {
        $this->requestHandlerFactory->create($this->containerRegistry)->handleRequest($request);
    }

    /**
     * {@inheritDoc}
     */
    public function createRequestFromGlobals(): Request
    {
        return Request::createFromGlobals();
    }

    /**
     * {@inheritDoc}
     */
    public function createRequestBuilder(): RequestBuilderInterface
    {
        return $this->requestBuilderFactory->create();
    }

    /**
     * {@inheritDoc}
     */
    public function generateUrlByRouteName(string $routeName, array $parameters = []): string
    {
        return $this->urlGeneratorFactory
            ->create()
            ->generate($routeName, $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
    }
}