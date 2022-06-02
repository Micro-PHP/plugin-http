<?php

namespace Micro\Plugin\Http\Business\RouteConfiguration;

interface ReaderResolverConfigurationInterface
{
    /**
     * @param string $format
     * @return bool
     */
    public function supports(string $format): bool;

    /**
     * @return RouteConfigurationReaderInterface
     */
    public function resolve(): RouteConfigurationReaderInterface;
}