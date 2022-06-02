<?php

namespace Micro\Plugin\Http\Business\Context;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

class RequestContextFactory implements RequestContextFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(Request $request): RequestContext
    {
        $requestContext = new RequestContext();

        return $requestContext->fromRequest($request);
    }
}