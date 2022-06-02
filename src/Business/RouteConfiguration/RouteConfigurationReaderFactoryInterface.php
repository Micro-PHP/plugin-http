<?php

namespace Micro\Plugin\Http\Business\RouteConfiguration;

interface RouteConfigurationReaderFactoryInterface
{
    /**
     * @return RouteConfigurationReaderInterface
     */
    public function create(): RouteConfigurationReaderInterface;
}