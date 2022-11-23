<?php

namespace Micro\Plugin\Http\Business\RouteConfiguration;

class RouteResourceConfiguration implements RouteResourceConfigurationInterface
{
    /**
     * @param string $resource
     * @param string $format
     * @param string $prefix
     * @param string $parentFileDestination
     * @param string|null $host
     * @param array $handlers
     */
    public function __construct(
        private string $resource,
        private readonly string $format,
        private string $prefix,
        private readonly string $parentFileDestination,
        private readonly ?string $host,
        private array $handlers
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function addParentHandlers(array $handlers): void
    {
        if(!$handlers) {
            return;
        }

        foreach ($handlers as $handlerName => $handlerContent) {
            if(!isset($this->handlers[$handlerName])) {
                $this->handlers[$handlerName] = [];
            }

            $tmpHandlers = $this->handlers[$handlerName];

            $this->handlers[$handlerName] = array_unique(array_merge($handlerContent ?: [], $tmpHandlers));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * {@inheritDoc}
     */
    public function getParentFileDestination(): string
    {
        return $this->parentFileDestination;
    }

    /**
     * {@inheritDoc}
     */
    public function addParentPrefix(string $prefix): void
    {
        if(str_ends_with($this->getPrefix(), '/')) {
            $this->prefix = rtrim($this->prefix, '/');
        }

        if(!str_starts_with($prefix, '/')) {
            $prefix = '/' . $prefix;
        }

        $this->prefix = $prefix . $this->prefix;
    }

    /**
     * {@inheritDoc}
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * {@inheritDoc}
     */
    public function getResource(): string
    {
        if(!$this->getParentFileDestination()) {
            return $this->resource;
        }

        $resource = [
            (!\str_ends_with(DIRECTORY_SEPARATOR, $this->parentFileDestination) ?
                $this->parentFileDestination :
                $this->parentFileDestination . DIRECTORY_SEPARATOR
            ),
            $this->resource
        ];

        return implode(DIRECTORY_SEPARATOR, $resource);
    }

    /**
     * {@inheritDoc}
     */
    public function getFormat(): string
    {
        return $this->format ?? $this->resource;
    }

    /**
     * {@inheritDoc}
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * {@inheritDoc}
     */
    public function setResource(string $resource): void
    {
        $this->resource = $resource;
    }
}