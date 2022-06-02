<?php

namespace Micro\Plugin\Http\Business\Matcher;

interface UrlMatcherFactoryInterface
{
    /**
     * @return UrlMatcherInterface
     */
    public function create(): UrlMatcherInterface;
}