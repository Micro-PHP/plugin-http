<?php

namespace Micro\Plugin\Http\Business\RouteHandler\Extractor;

use Micro\Plugin\Http\Handler\HandlerAbstractFactoryInterface;

class RouteHandlerExtractorFactory implements RouteHandlerExtractorFactoryInterface
{
    /**
     * @param HandlerAbstractFactoryInterface $handlerAbstractFactory
     */
    public function __construct(private readonly HandlerAbstractFactoryInterface $handlerAbstractFactory)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function create(): RouteHandlerExtractorInterface
    {
        return new RouteHandlerExtractor(
            $this->handlerAbstractFactory,
        );
    }
}