<?php

namespace Micro\Plugin\Http\Business\Logger;

use Micro\Plugin\Http\Configuration\HttpPluginConfigurationInterface;
use Micro\Plugin\Logger\LoggerFacadeInterface;

class HttpLoggerFactory implements HttpLoggerFactoryInterface
{
    /**
     * @param LoggerFacadeInterface $loggerFacade
     * @param HttpPluginConfigurationInterface $httpPluginConfiguration
     */
    public function __construct(
        private readonly LoggerFacadeInterface $loggerFacade,
        private readonly HttpPluginConfigurationInterface $httpPluginConfiguration
    )
    {

    }

    /**
     * @return HttpLoggerInterface
     */
    public function create(): HttpLoggerInterface
    {
        return new HttpLogger(
            $this->loggerFacade,
            $this->httpPluginConfiguration
        );
    }
}