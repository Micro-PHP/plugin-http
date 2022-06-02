<?php

namespace Micro\Plugin\Http\Business\RouteConfiguration\Yaml;

use Micro\Plugin\Http\Business\RouteConfiguration\RouteConfigurationReaderFactoryInterface;
use Micro\Plugin\Http\Business\RouteConfiguration\RouteConfigurationReaderInterface;

class YamlRouteConfigurationReaderFactory implements RouteConfigurationReaderFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(): RouteConfigurationReaderInterface
    {
        return new YamlRouteReader();
    }
}