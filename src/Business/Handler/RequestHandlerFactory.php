<?php

namespace Micro\Plugin\Http\Business\Handler;

use Micro\Component\DependencyInjection\Autowire\AutowireHelperFactoryInterface;
use Micro\Component\DependencyInjection\ContainerRegistryInterface;
use Micro\Plugin\Http\Business\Logger\HttpLoggerFactoryInterface;
use Micro\Plugin\Http\Business\Matcher\UrlMatcherFactoryInterface;
use Micro\Plugin\Http\Business\RouteHandler\Extractor\RouteHandlerExtractorFactoryInterface;
use Micro\Plugin\Http\Business\RouteHandler\Extractor\RouteHandlerExtractorInterface;

class RequestHandlerFactory implements RequestHandlerFactoryInterface
{
    /**
     * @param UrlMatcherFactoryInterface $urlMatcherFactory
     * @param AutowireHelperFactoryInterface $autowireHelperFactory
     * @param RouteHandlerExtractorFactoryInterface $routeHandlerExtractorFactory
     * @param HttpLoggerFactoryInterface $httpLoggerFactory
     */
    public function __construct(
        private readonly UrlMatcherFactoryInterface $urlMatcherFactory,
        private readonly AutowireHelperFactoryInterface $autowireHelperFactory,
        private readonly RouteHandlerExtractorFactoryInterface $routeHandlerExtractorFactory,
        private readonly HttpLoggerFactoryInterface $httpLoggerFactory
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function create(?ContainerRegistryInterface $containerRegistry = null): RequestHandlerInterface
    {
        return new RequestHandler(
            $this->urlMatcherFactory->create(),
            $this->autowireHelperFactory->create(),
            $this->routeHandlerExtractorFactory->create(),
            $this->httpLoggerFactory->create(),
            $containerRegistry
        );
    }
}