<?php

namespace Micro\Plugin\Http\Business\RouteConfiguration;

interface RouteResourceConfigurationInterface
{
    /**
     * @return string
     */
    public function getPrefix(): string;

    /**
     * @param string $prefix
     *
     * @return void
     */
    public function addParentPrefix(string $prefix): void;

    /**
     * @param string $resource
     * @return void
     *
     */
    public function setResource(string $resource): void;

    /**
     * @return string
     */
    public function getResource(): string;

    /**
     * @return string
     */
    public function getFormat(): string;

    /**
     * @return string
     */
    public function getParentFileDestination(): string;

    /**
     * @return string|null
     */
    public function getHost(): ?string;

    /**
     * @param array $handlers
     *
     * @return void
     */
    public function addParentHandlers(array $handlers): void;

    /**
     * @return array
     */
    public function getHandlers(): array;
}