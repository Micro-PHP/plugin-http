<?php

namespace Micro\Plugin\Http\Business\Route;

use Micro\Plugin\Http\Business\RouteProvider\RouteProviderFactoryInterface;
use Symfony\Component\Routing\RouteCollection;

class RouteCollectionFactory implements RouteCollectionFactoryInterface
{

    public function __construct(private readonly RouteProviderFactoryInterface $routeProviderFactory)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function create(): RouteCollection
    {
        $routeCollection = new RouteCollection();

        $this->routeProviderFactory
            ->create()
            ->provide($routeCollection);

        return $routeCollection;
    }
}