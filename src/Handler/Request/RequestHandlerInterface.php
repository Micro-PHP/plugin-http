<?php

namespace Micro\Plugin\Http\Handler\Request;

use Micro\Plugin\Http\Exception\HttpException;

interface RequestHandlerInterface
{
    /**
     * @param RequestHandlerContextInterface $requestHandlerContext
     *
     * @return void
     *
     * @throws HttpException
     */
    public function handle(RequestHandlerContextInterface $requestHandlerContext): void;
}