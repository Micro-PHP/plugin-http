<?php

namespace Micro\Plugin\Http\Business\Matcher\Symfony;

use Micro\Plugin\Http\Business\Route\RouteCollectionFactoryInterface;
use Micro\Plugin\Http\Configuration\HttpPluginConfigurationInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher;
use Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherDumper;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

class SymfonyUrlMatcherFactory
{
    public function __construct(
        private readonly RouteCollectionFactoryInterface $routeCollectionFactory,
        private readonly HttpPluginConfigurationInterface $httpPluginConfiguration,
        private readonly LoggerInterface $logger
    )
    {

    }

    protected function getRouteCacheFile()
    {
        return $this->httpPluginConfiguration->getRouteCacheFile();
    }

    protected function getRouteCacheDir(): string
    {
        return $this->httpPluginConfiguration->getRouteCacheDir();
    }

    /**
     * @param RequestContext $requestContext
     * @return UrlMatcherInterface
     * @throws \Exception
     */
    public function create(RequestContext $requestContext): UrlMatcherInterface
    {
        $compilerRoutes = null;
        $isProductionMode = $this->httpPluginConfiguration->isProductionMode();
        $cacheFile = $this->getRouteCacheFile();
        $isCacheFileExists =  file_exists($cacheFile);

        if(!$isCacheFileExists || !$isProductionMode) {
            $cacheDir = $this->getRouteCacheDir();
            $isCacheDirExists = file_exists($cacheDir);
            if(!$isCacheDirExists) {
                if(!mkdir($cacheDir, 0755, true)) {
                    $this->logger->warning(sprintf('Could not create routes cache path "%s"', $cacheDir));
                }
            }

            $routes = $this->routeCollectionFactory->create();
            $compiledRoutes = (new CompiledUrlMatcherDumper($routes))->dump();
            if(false === file_put_contents($this->getRouteCacheFile(), $compiledRoutes)) {
                $this->logger->warning(sprintf('Could not create routes cache file "%s"', $cacheFile));
            }
        }

        $compiledRoutes = include $this->getRouteCacheFile();

        return new CompiledUrlMatcher($compiledRoutes, $requestContext);
    }
}