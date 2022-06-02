<?php

namespace Micro\Plugin\Http\Facade;

use Micro\Plugin\Http\Business\Request\RequestBuilderInterface;
use Micro\Plugin\Http\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

interface HttpFacadeInterface
{
    /**
     * @param Request $request
     *
     * @throws MethodNotAllowedException
     * @throws NoConfigurationException
     * @throws ResourceNotFoundException
     */
    public function match(Request $request);

    /**
     * @param Request $request
     *
     * @throws MethodNotAllowedException
     * @throws NoConfigurationException
     * @throws ResourceNotFoundException
     */
    public function handleRequest(Request $request): void;

    /**
     * @return Request
     */
    public function createRequestFromGlobals(): Request;

    /**
     * @return RequestBuilderInterface
     */
    public function createRequestBuilder(): RequestBuilderInterface;

    /**
     * @param string $routeName
     * @param array $parameters
     *
     * @return string
     *
     * @throws RouteNotFoundException              If the named route doesn't exist
     * @throws MissingMandatoryParametersException When some parameters are missing that are mandatory for the route
     * @throws InvalidParameterException           When a parameter value for a placeholder is not correct because
     *                                             it does not match the requirement
     */
    public function generateUrlByRouteName(string $routeName, array $parameters = []): string;
}