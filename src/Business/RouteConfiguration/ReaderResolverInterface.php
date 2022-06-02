<?php

namespace Micro\Plugin\Http\Business\RouteConfiguration;

interface ReaderResolverInterface
{
    /**
     * @param string $format
     *
     * @return RouteConfigurationReaderInterface
     */
    public function resolve(string $format): RouteConfigurationReaderInterface;
}