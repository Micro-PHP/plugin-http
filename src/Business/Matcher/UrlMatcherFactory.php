<?php

namespace Micro\Plugin\Http\Business\Matcher;

use Micro\Plugin\Http\Business\Route\RouteCollectionFactoryInterface;
use Micro\Plugin\Http\Configuration\HttpPluginConfigurationInterface;
use Micro\Plugin\Logger\LoggerFacadeInterface;

class UrlMatcherFactory implements UrlMatcherFactoryInterface
{
    public function __construct(
        private RouteCollectionFactoryInterface $routeCollectionFactory,
        private readonly HttpPluginConfigurationInterface $httpPluginConfiguration,
        private readonly LoggerFacadeInterface $loggerFacade,
    )
    {

    }

    /**
     * @return UrlMatcherInterface
     */
    public function create(): UrlMatcherInterface
    {
        return new UrlMatcher(
            $this->routeCollectionFactory,
            $this->httpPluginConfiguration,
            $this->loggerFacade->getLogger($this->httpPluginConfiguration->getLoggerNameError())
        );
    }
}