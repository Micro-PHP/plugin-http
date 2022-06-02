<?php

namespace Micro\Plugin\Http\Business\RouteConfiguration\Yaml;

use Micro\Plugin\Http\Business\RouteConfiguration\RouteConfiguration;
use Micro\Plugin\Http\Business\RouteConfiguration\RouteConfigurationInterface;
use Micro\Plugin\Http\Business\RouteConfiguration\RouteConfigurationReaderInterface;
use Micro\Plugin\Http\Business\RouteConfiguration\RouteResourceConfiguration;
use Micro\Plugin\Http\Business\RouteConfiguration\RouteResourceConfigurationInterface;
use Symfony\Component\Yaml\Yaml;

class YamlRouteReader implements RouteConfigurationReaderInterface
{
    const SECTION_ROUTING = 'routes';
    const SECTION_ROUTE_RESOURCE_DEST = 'resource';
    const SECTION_ROUTE_RESOURCE_EXT = 'format';
    const SECTION_ROUTE_RESOURCE_PREFIX = 'prefix';
    const SECTION_HANDLER = 'handler';

    /**
     * {@inheritDoc}
     */
    public function read(RouteResourceConfigurationInterface $routeResourceConfiguration): iterable
    {
        $destination = $routeResourceConfiguration->getResource();
        $host = $routeResourceConfiguration->getHost() ?? '';

        $resource = $this->parseFile($destination);
        $routes = $resource[self::SECTION_ROUTING] ?? [];
        $parentHandlers = $routeResourceConfiguration->getHandlers();

        foreach ($routes as $name => $routeConfiguration) {
            if(isset($routeConfiguration[self::SECTION_ROUTE_RESOURCE_DEST])) {

                $childResource = $this->createRouteResourceConfiguration(
                    $name,
                    $routeConfiguration,
                    dirname(realpath($destination)),
                );

                $childResource->addParentHandlers($parentHandlers);

                yield $childResource;

                continue;
            }

            $routeResultConfiguration = $this->createRouteConfiguration($name, $routeConfiguration, $host, []);
            $routeResultConfiguration->addParentHandlers($routeConfiguration['options']['handler'] ?? []);

            yield $routeResultConfiguration;
        }
    }

    /**
     * @param string $name
     * @param array $config
     * @param string|null $host
     * @param array $parentHandlers
     *
     * @return RouteConfigurationInterface
     */
    protected function createRouteConfiguration(string $name, array $config, ?string $host, array $parentHandlers): RouteConfigurationInterface
    {
        $priority = $parameters['priority'] ?? 0;
        //$handlers = array_merge($parentHandlers, $config[self::SECTION_HANDLER] ?? []);
        $routeConfiguration = new RouteConfiguration(
            $name,
            $config,
            $priority,
            [],
            $host,
        );

        return $routeConfiguration;
    }

    /**
     * @param string $name
     * @param array $config
     * @param string $currentFileDestination
     *
     * @return RouteResourceConfigurationInterface
     */
    protected function createRouteResourceConfiguration(string $name, array $config, string $currentFileDestination): RouteResourceConfigurationInterface
    {
        $stringVars = [
            self::SECTION_ROUTE_RESOURCE_DEST,
            self::SECTION_ROUTE_RESOURCE_EXT,
            self::SECTION_ROUTE_RESOURCE_PREFIX,
        ];

        $exceptions = [];

        foreach ($stringVars as $var) {
            $varVal = $config[$var] ?? '';
            $config[$var] = $varVal;

            if(!is_string($varVal)) {
                $exceptions[$var] = gettype($varVal);
            }
        }

        if(!$exceptions) {
            return new RouteResourceConfiguration(
                $config[self::SECTION_ROUTE_RESOURCE_DEST],
                $config[self::SECTION_ROUTE_RESOURCE_EXT] ?? '',
                $config[self::SECTION_ROUTE_RESOURCE_PREFIX] ?? '',
                $currentFileDestination,
                $config['host'] ?? null,
                $config[self::SECTION_HANDLER] ?? []
            );
        }

        $messages = [];
        foreach ($exceptions as $var => $type) {
            $messages []= sprintf(' - Property "%s" should be string, %s given', $var, ucfirst($type));
        }

        throw new \InvalidArgumentException(sprintf(
            "Route \"%s\" configuration invalid: \n%s",
                $name, implode("\n", $messages)
            )
        );
    }

    /**
     * @param string $fileDestination
     * @return array
     */
    protected function parseFile(string $fileDestination): array
    {
        return Yaml::parseFile($fileDestination);
    }
}