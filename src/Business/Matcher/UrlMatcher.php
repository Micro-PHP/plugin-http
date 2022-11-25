<?php

namespace Micro\Plugin\Http\Business\Matcher;

use Micro\Plugin\Http\Business\Matcher\Symfony\SymfonyUrlMatcherFactory;
use Micro\Plugin\Http\Business\Route\RouteCollectionFactoryInterface;
use Micro\Plugin\Http\Configuration\HttpPluginConfigurationInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface as SymfonyUrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;

class UrlMatcher implements UrlMatcherInterface
{
    /**
     * @var SymfonyUrlMatcher|null
     */
    private ?SymfonyUrlMatcher $symfonyUrlMatcher = null;

    /**
     * @param RouteCollectionFactoryInterface $routeCollectionFactory
     * @param HttpPluginConfigurationInterface $httpPluginConfiguration
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly RouteCollectionFactoryInterface $routeCollectionFactory,
        private readonly HttpPluginConfigurationInterface $httpPluginConfiguration,
        private readonly LoggerInterface $logger
    )
    {
    }

    /**
     * TODO: InvalidArgumentException !
     * {@inheritDoc}
     */
    public function match(Request $request): Route
    {
        $result = $this->getSymfonyUrlMatcherInstance($request)->matchRequest($request);
        $routeContext = $result['route_context'] ?? [];
        $routeConfig = $routeContext['route'] ?? [];
        $route = new Route(
            $routeConfig['path'],
            $routeConfig['defaults'] ?? [],
            $routeConfig['requirements'] ?? [],
            $routeContext,
            $routeConfig['host'] ?? '',
            $routeConfig['schemes'] ?? [],
            $routeConfig['methods'] ?? []
        );

        unset($result['route_context']);

        $request->query->add($result);
        $request->attributes->set('route', $route);

        return $route;
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

            // todo: Injectable
            $sumf = new SymfonyUrlMatcherFactory(
                $this->routeCollectionFactory,
                $this->httpPluginConfiguration,
                $this->logger
            );

            $this->symfonyUrlMatcher = $sumf->create($requestContext);
        }

        return $this->symfonyUrlMatcher;
    }
}