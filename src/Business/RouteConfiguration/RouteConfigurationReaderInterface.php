<?php

namespace Micro\Plugin\Http\Business\RouteConfiguration;


interface RouteConfigurationReaderInterface
{
    /**
     * @param RouteResourceConfigurationInterface $routeResourceConfiguration
     *
     * @return iterable
     */
    public function read(RouteResourceConfigurationInterface $routeResourceConfiguration): iterable;
}