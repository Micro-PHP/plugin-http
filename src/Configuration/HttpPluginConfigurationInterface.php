<?php

namespace Micro\Plugin\Http\Configuration;

interface HttpPluginConfigurationInterface
{
    const EXT_YAML = 'yaml';
    const EXT_YML = 'yml';

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