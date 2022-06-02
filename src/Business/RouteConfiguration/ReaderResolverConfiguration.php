<?php

namespace Micro\Plugin\Http\Business\RouteConfiguration;

class ReaderResolverConfiguration implements ReaderResolverConfigurationInterface
{
    /**
     * @param RouteConfigurationReaderFactoryInterface $routeConfigurationReaderFactory
     * @param string $format
     */
    public function __construct(
        private readonly RouteConfigurationReaderFactoryInterface $routeConfigurationReaderFactory,
        private readonly string $format
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $format): bool
    {
        return mb_strtolower($this->format) === mb_strtolower($format);
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(): RouteConfigurationReaderInterface
    {
        return $this->routeConfigurationReaderFactory->create();
    }
}