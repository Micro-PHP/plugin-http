<?php

namespace Micro\Plugin\Http\Configuration;

interface HttpPluginConfigurationInterface
{
    const EXT_YAML = 'yaml';
    const EXT_YML = 'yml';

    /**
     * @return bool
     */
    public function isProductionMode(): bool;

    /**
     * @return string
     */
    public function getRouteCacheFile(): string;

    /**
     * @return string
     */
    public function getRouteCacheDir(): string;

    /**
     * @return string
     */
    public function getRouteConfigurationExtension(): string;

    /**
     * @return iterable<string>
     */
    public function getConfigurationDestination(): iterable;

    /**
     * @return string|null
     */
    public function getLoggerNameError(): ?string;

    /**
     * @return string|null
     */
    public function getLoggerNameAccess(): ?string;


}