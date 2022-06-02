<?php

namespace Micro\Plugin\Http;

use Micro\Framework\Kernel\Configuration\PluginConfiguration;
use Micro\Plugin\Http\Configuration\HttpPluginConfigurationInterface;
use Micro\Plugin\Logger\LoggerFacadeInterface;

class HttpPluginConfiguration extends PluginConfiguration implements HttpPluginConfigurationInterface
{
    const CFG_LOGGER_ACCESS = 'HTTP_LOGGER_ACCESS';
    const CFG_LOGGER_ERROR = 'HTTP_LOGGER_ERROR';

    /**
     * {@inheritDoc}
     */
    public function getRouteConfigurationExtension(): string
    {
        return self::EXT_YAML;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigurationDestination(): iterable
    {
        yield $this->configuration->get('BASE_PATH') . '/etc/routing/routing.yaml';
    }

    /**
     * {@inheritDoc}
     */
    public function getLoggerNameAccess(): ?string
    {
        return $this->configuration->get(self::CFG_LOGGER_ACCESS, LoggerFacadeInterface::LOGGER_DEFAULT);
    }

    /**
     * {@inheritDoc}
     */
    public function getLoggerNameError(): ?string
    {
        return $this->configuration->get(self::CFG_LOGGER_ERROR, LoggerFacadeInterface::LOGGER_DEFAULT);
    }
}