<?php

namespace Micro\Plugin\Http\Business\RouteConfiguration;


class RouteConfiguration implements RouteConfigurationInterface
{
    /**
     * @param string $name
     * @param array $configuration
     * @param int $priority
     * @param array $handlers
     * @param string|null $host
     */
    public function __construct(
        private readonly string $name,
        private readonly array $configuration,
        private readonly int $priority,
        private array $handlers = [],
        private readonly ?string $host = null
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
            if(!$this->handlers[$handlerName]) {
                $this->handlers[$handlerName] = [];
            }

            $tmpHandlers = $this->handlers[$handlerName];

            $this->handlers[$handlerName] = array_merge($handlerContent ?: [], $tmpHandlers);
        }
    }

    /**
     * @return array
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }
}