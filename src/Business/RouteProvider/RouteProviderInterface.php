<?php

namespace Micro\Plugin\Http\Business\RouteProvider;

use Symfony\Component\Routing\RouteCollection;

interface RouteProviderInterface
{
    /**
     * @param RouteCollection $routeCollection
     *
     * @return void
     */
    public function provide(RouteCollection $routeCollection): void;
}