<?php

namespace Micro\Plugin\Http\Business\Matcher;

use Micro\Plugin\Http\Business\Route\RouteCollectionFactoryInterface;

class UrlMatcherFactory implements UrlMatcherFactoryInterface
{
    public function __construct(
        private RouteCollectionFactoryInterface $routeCollectionFactory
    )
    {

    }

    /**
     * @return UrlMatcherInterface
     */
    public function create(): UrlMatcherInterface
    {
        return new UrlMatcher($this->routeCollectionFactory->create());
    }
}