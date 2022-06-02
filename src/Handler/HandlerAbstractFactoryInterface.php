<?php

namespace Micro\Plugin\Http\Handler;

use Micro\Plugin\Http\Handler\Request\RequestHandlerInterface;
use Micro\Plugin\Http\Handler\Response\ResponseHandlerInterface;

interface HandlerAbstractFactoryInterface
{
    /**
     * @param string $handlerClass
     *
     * @return RequestHandlerInterface|ResponseHandlerInterface
     */
    public function create(string $handlerClass): RequestHandlerInterface|ResponseHandlerInterface;
}