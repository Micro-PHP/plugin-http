<?php

namespace Micro\Plugin\Http\Business\Handler;

use Micro\Component\DependencyInjection\ContainerRegistryInterface;

interface RequestHandlerFactoryInterface
{
    /**
     * @param ContainerRegistryInterface|null $containerRegistry
     *
     * @return RequestHandlerInterface
     */
    public function create(?ContainerRegistryInterface $containerRegistry = null): RequestHandlerInterface;
}