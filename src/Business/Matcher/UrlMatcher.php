<?php

namespace Micro\Plugin\Http\Business\Matcher;

use Micro\Plugin\Http\Exception\RouteNotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher as SymfonyUrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Throwable;

class UrlMatcher implements UrlMatcherInterface
{
    /**
     * @var SymfonyUrlMatcher|null
     */
    private ?SymfonyUrlMatcher $symfonyUrlMatcher = null;

    public function __construct(private readonly RouteCollection $routeCollection)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function match(Request $request): Route
    {

        $result = $this->getSymfonyUrlMatcherInstance($request)->matchRequest($request);
        $this->routeCollection->get($result['_route']);
        $request->query->add($result);

        return $this->routeCollection->get($result['_route']);
    }

    /**
     * @param Request $request
     *
     * @return SymfonyUrlMatcher
     */
    protected function getSymfonyUrlMatcherInstance(Request $request): SymfonyUrlMatcher
    {
        if(!$this->symfonyUrlMatcher) {
            $requestContext = new RequestContext();
            $requestContext->fromRequest($request);

            $this->symfonyUrlMatcher = new SymfonyUrlMatcher($this->routeCollection, $requestContext);
        }

        return $this->symfonyUrlMatcher;
    }
}