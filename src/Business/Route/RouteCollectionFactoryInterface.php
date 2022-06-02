<?php

namespace Micro\Plugin\Http\Business\Route;

use Symfony\Component\Routing\RouteCollection;

interface RouteCollectionFactoryInterface
{
    /**
     * @return RouteCollection
     */
    public function create(): RouteCollection;
}