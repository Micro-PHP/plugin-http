<?php

namespace Micro\Plugin\Http\Business\Context;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

interface RequestContextFactoryInterface
{
    /**
     * @param Request $request
     *
     * @return RequestContext
     */
    public function create(Request $request): RequestContext;
}