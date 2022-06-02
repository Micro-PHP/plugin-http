<?php

namespace Micro\Plugin\Http\Business\RouteProvider;

use Micro\Plugin\Http\Business\RouteConfiguration\ReaderResolverInterface;
use Micro\Plugin\Http\Business\RouteConfiguration\RouteConfigurationInterface;
use Micro\Plugin\Http\Business\RouteConfiguration\RouteResourceConfiguration;
use Micro\Plugin\Http\Business\RouteConfiguration\RouteResourceConfigurationInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteProvider implements RouteProviderInterface
{
    /**
     * @param iterable $routeConfigurationDestinationCollection
     * @param ReaderResolverInterface $readerResolver
     */
    public function __construct(
        private readonly iterable                          $routeConfigurationDestinationCollection,
        private readonly ReaderResolverInterface           $readerResolver
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function provide(RouteCollection $routeCollection): void
    {
        $resources = [];
        $destinations = [];
        $parentHandlers = [];

        foreach ($this->routeConfigurationDestinationCollection as $dest) {
             $routeResourceConfiguration = $this->createRouteResourceConfiguration(
                $dest,
                $dest,
                $dest
            );
            $destinations [] = $routeResourceConfiguration;
        }

        //TODO: Implements circular exception !
        while(true) {
            foreach ($this->provideRoutesFromDestinations($destinations, $routeCollection, $parentHandlers) as $resource) {
                $resources[] = $resource;
            }

            $destinations = $resources;
            $resources = [];

            if(!$destinations) {
                break;
            }
        }

        dump($routeCollection);
    }

    /**
     * @todo: make factory
     *
     * @param string|null $name
     * @param string $path
     * @param string|null $format
     * @param string|null $prefix
     * @param string $parentFileDestination
     * @param string|null $host
     *
     * @return RouteResourceConfiguration
     */
    protected function createRouteResourceConfiguration(?string $name, string $path, ?string $format, ?string $prefix = '', string $parentFileDestination = '', ?string $host = null): RouteResourceConfiguration
    {
        return new RouteResourceConfiguration(
            $path,
            $format,
            $prefix,
            $parentFileDestination,
            $host,
            []
        );
    }

    /**
     * @param iterable $destinationCollection
     * @param RouteCollection $routeCollection
     * @param array $parentHandlers
     *
     * @return iterable
     */
    protected function provideRoutesFromDestinations(iterable $destinationCollection, RouteCollection $routeCollection, array $parentHandlers): iterable
    {
        foreach ($destinationCollection as $routeConfiguration) {
            foreach ($this->readerResolver
                         ->resolve($routeConfiguration->getFormat())
                         ->read($routeConfiguration) as $routeItem
            ) {
                /** @var RouteResourceConfigurationInterface $routeConfiguration */
                $routeItem->addParentHandlers($routeConfiguration->getHandlers());

                if($routeItem instanceof RouteConfigurationInterface) {
                    $routeCollection->add(
                        $routeItem->getName(),
                        $this->createRoute($routeItem, $routeConfiguration->getPrefix(), $routeItem->getHost()),
                        $routeItem->getPriority()
                    );

                    continue;
                }

                /** @var RouteResourceConfiguration $routeItem */
                $routeItem->addParentPrefix($routeConfiguration->getPrefix());

                yield $routeItem;
            }
        }
    }

    /**
     * @param RouteConfigurationInterface $configuration
     *
     * @TODO: Make factory
     *
     * @see: Route::__construct
     *
     * @return Route
     */
    protected function createRoute(RouteConfigurationInterface $configuration, ?string $prefix = '', ?string $parentHost = null): Route
    {
        $routeData = $configuration->getConfiguration();

        $prefix = rtrim($prefix, '/');
        $path = ltrim($routeData['path'], '/' );

        $path = $prefix . '/'. $path;
        $host = $routeData['host'] ?? $parentHost;
        $defaults = $routeData['defaults'] ?? [];
        $requirements = $routeData['requirements'] ?? [];
        $options = $routeData['options'] ?? [];
        $schemes = $routeData['schemes'] ?? [];
        $methods = $routeData['methods'] ?? [];
        $condition = $routeData['condition'] ?? null;
        $options['handler'] = $configuration->getHandlers();

        return new Route(
            $path,
            $defaults,
            $requirements,
            $options,
            $host,
            $schemes,
            $methods,
            $condition
        );
    }
}