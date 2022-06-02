<?php

namespace Micro\Plugin\Http\Business\Matcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Route;

interface UrlMatcherInterface
{
    /**
     * @param Request $request
     *
     * @throws MethodNotAllowedException
     * @throws NoConfigurationException
     * @throws ResourceNotFoundException
     */
    public function match(Request $request): Route;
}