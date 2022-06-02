<?php

namespace Micro\Plugin\Http\Business\Handler;

use Micro\Component\DependencyInjection\ContainerRegistryInterface;
use Micro\Plugin\Http\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RequestHandlerInterface
{
    /**
     * @param Request $request
     *
     * @return void
     *
     * @throws HttpException
     */
    public function handleRequest(Request $request): void;
}