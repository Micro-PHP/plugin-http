<?php

namespace Micro\Plugin\Http\Handler\Response;

use Micro\Plugin\Http\Exception\HttpException;

interface ResponseHandlerInterface
{
    /**
     * @param ResponseHandlerContextInterface $responseHandlerContext
     *
     * @return void
     *
     * @throws HttpException
     */
    public function handle(ResponseHandlerContextInterface $responseHandlerContext): void;
}