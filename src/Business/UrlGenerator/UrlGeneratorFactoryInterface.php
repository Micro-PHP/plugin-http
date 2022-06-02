<?php

namespace Micro\Plugin\Http\Business\UrlGenerator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

interface UrlGeneratorFactoryInterface
{
    /**
     * @param Request|null $request
     *
     * @return UrlGeneratorInterface
     */
    public function create(?Request $request = null): UrlGeneratorInterface;
}