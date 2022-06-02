<?php

namespace Micro\Plugin\Http\Handler;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractHandlerContext implements HandlerContextInterface
{
    /**
     * @param Request $request
     */
    public function __construct(
        private readonly Request $request
    )
    {

    }

    /**
     * {@inheritDoc}
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}