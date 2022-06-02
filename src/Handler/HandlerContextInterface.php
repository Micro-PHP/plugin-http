<?php

namespace Micro\Plugin\Http\Handler;

use Symfony\Component\HttpFoundation\Request;

interface HandlerContextInterface
{
    /**
     * @return Request
     */
    public function getRequest(): Request;
}