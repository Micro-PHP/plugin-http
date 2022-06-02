<?php

namespace Micro\Plugin\Http\Handler;

use Micro\Component\DependencyInjection\Autowire\AutowireHelperFactoryInterface;
use Micro\Plugin\Http\Handler\Request\RequestHandlerInterface;
use Micro\Plugin\Http\Handler\Response\ResponseHandlerInterface;

class HandlerAbstractFactory implements HandlerAbstractFactoryInterface
{
    /**
     * @param AutowireHelperFactoryInterface $autowireHelperFactory
     */
    public function __construct(
        private readonly AutowireHelperFactoryInterface $autowireHelperFactory
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function create(string $handlerClass): RequestHandlerInterface|ResponseHandlerInterface
    {
        $handlerAutowired = $this->createHandlerAutowiredCallback($handlerClass);

        return $handlerAutowired();
    }

    /**
     * @param string $handlerClass
     *
     * @return callable
     */
    protected function createHandlerAutowiredCallback(string $handlerClass): callable
    {
        return $this->autowireHelperFactory->create()->autowire($handlerClass);
    }
}