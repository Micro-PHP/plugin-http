<?php

namespace Micro\Plugin\Http\Business\Request;

class RequestBuilderFactory implements RequestBuilderFactoryInterface
{
    /**
     * @return RequestBuilderInterface
     */
    public function create(): RequestBuilderInterface
    {
        return new RequestBuilder();
    }
}