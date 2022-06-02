<?php

namespace Micro\Plugin\Http\Business\RouteConfiguration;

interface RouteConfigurationInterface
{
    /**
     * @param array $handlers
     * @return void
     */
    public function addParentHandlers(array $handlers): void;

    /**
     * @return array
     */
    public function getHandlers(): array;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return int
     */
    public function getPriority(): int;

    /**
     * @return array
     */
    public function getConfiguration(): array;

    /**
     * @return string|null
     */
    public function getHost(): ?string;
}