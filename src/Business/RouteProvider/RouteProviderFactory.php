<?php

namespace Micro\Plugin\Http\Business\RouteProvider;


use Micro\Plugin\Http\Business\RouteConfiguration\ReaderResolverInterface;

class RouteProviderFactory implements RouteProviderFactoryInterface
{
    /**
     * @param ReaderResolverInterface $readerResolver
     * @param iterable $routeConfigurationDestinationCollection
     */
    public function __construct(
        private readonly ReaderResolverInterface $readerResolver,
        private readonly iterable $routeConfigurationDestinationCollection

    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function create(): RouteProviderInterface
    {
        return new RouteProvider(
            $this->routeConfigurationDestinationCollection,
            $this->readerResolver
        );
    }
}