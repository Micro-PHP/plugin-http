<?php

namespace Micro\Plugin\Http\Business\Logger;


use Micro\Plugin\Http\Configuration\HttpPluginConfigurationInterface;
use Micro\Plugin\Logger\LoggerFacadeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HttpLogger implements HttpLoggerInterface
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
     * {@inheritDoc}
     */
    public function access(Request $request, Response $response, array $context = []): void
    {
        $this->loggerFacade->getLogger($this->httpPluginConfiguration->getLoggerNameAccess())
            ->info(
                $this->createBaseMessage($request, $response),
                $context
            );
    }

    /**
     * {@inheritDoc}
     */
    public function warning(Request $request, ?Response $response, string $message = null, array $context = []): void
    {
        $this->loggerFacade->getLogger($this->httpPluginConfiguration->getLoggerNameError())->warning(
            $this->createBaseMessage($request, $response) . ' Message: ' . $message,
            $context
        );
    }

    /**
     * {@inheritDoc}
     */
    public function error(\Throwable $e, Request $request, ?Response $response = null, array $context = []): void
    {
        $this->loggerFacade->getLogger($this->httpPluginConfiguration->getLoggerNameError())->critical(
            $e->getMessage(),
            $context
        );
    }

    /**
     * @param Request $request
     * @param Response|null $response
     * @return string
     */
    protected function createBaseMessage(Request $request, ?Response $response): string
    {
        return sprintf(
            '%s [%d] %s',
            $request->getMethod(),
            $response?->getStatusCode(),
            $request->getRequestUri(),
        );
    }
}