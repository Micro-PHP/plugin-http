<?php

namespace Micro\Plugin\Http\Business\RouteConfiguration\Yaml;

use Micro\Plugin\Configuration\Helper\Facade\ConfigurationHelperFacadeInterface;
use Micro\Plugin\Http\Business\RouteConfiguration\RouteConfigurationReaderFactoryInterface;
use Micro\Plugin\Http\Business\RouteConfiguration\RouteConfigurationReaderInterface;

class YamlRouteConfigurationReaderFactory implements RouteConfigurationReaderFactoryInterface
{
    public function __construct(private readonly ConfigurationHelperFacadeInterface $configurationHelperFacade)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function create(): RouteConfigurationReaderInterface
    {
        return new YamlRouteReader($this->configurationHelperFacade);
    }
}