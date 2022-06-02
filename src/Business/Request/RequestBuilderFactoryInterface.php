<?php

namespace Micro\Plugin\Http\Business\Request;

interface RequestBuilderFactoryInterface
{
    /**
     * @return RequestBuilderInterface
     */
    public function create(): RequestBuilderInterface;
}