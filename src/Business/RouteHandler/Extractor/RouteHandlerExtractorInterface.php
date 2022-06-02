<?php

namespace Micro\Plugin\Http\Business\RouteHandler\Extractor;

use Micro\Plugin\Http\Handler\Request\RequestHandlerInterface;
use Micro\Plugin\Http\Handler\Response\ResponseHandlerInterface;
use Symfony\Component\Routing\Route;

interface RouteHandlerExtractorInterface
{
    /**
     * @param Route $route
     *
     * @return iterable<RequestHandlerInterface>
     */
    public function extractRequestHandlers(Route $route): iterable;

    /**
     * @param Route $route
     *
     * @return iterable<ResponseHandlerInterface>
     */
    public function extractResponseHandlers(Route $route): iterable;
}