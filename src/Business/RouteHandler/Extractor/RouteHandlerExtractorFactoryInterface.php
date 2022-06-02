<?php

namespace Micro\Plugin\Http\Business\RouteHandler\Extractor;

use Symfony\Component\Routing\Route;

interface RouteHandlerExtractorFactoryInterface
{
    /**
     * @return RouteHandlerExtractorInterface
     */
    public function create(): RouteHandlerExtractorInterface;
}