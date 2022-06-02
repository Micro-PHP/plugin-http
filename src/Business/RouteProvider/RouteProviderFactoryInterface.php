<?php

namespace Micro\Plugin\Http\Business\RouteProvider;

interface RouteProviderFactoryInterface
{
    /**
     * @return RouteProviderInterface
     */
    public function create(): RouteProviderInterface;
}