<?php

namespace Micro\Plugin\Http\Business\UrlGenerator;

use Micro\Plugin\Http\Business\Context\RequestContextFactoryInterface;
use Micro\Plugin\Http\Business\Route\RouteCollectionFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlGeneratorFactory implements UrlGeneratorFactoryInterface
{

    public function __construct(
        private readonly RouteCollectionFactoryInterface $routeCollectionFactory,
        private readonly RequestContextFactoryInterface $contextFactory
    )
    {

    }

    /**
     * {@inheritDoc}
     */
    public function create(?Request $request = null): UrlGeneratorInterface
    {
        if($request === null) {
            $request = Request::createFromGlobals();
        }

        return new UrlGenerator(
            $this->routeCollectionFactory->create(),
            $this->contextFactory->create($request)
        );
    }
}