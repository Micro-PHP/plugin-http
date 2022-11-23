<?php

namespace Micro\Plugin\Http\Business\RouteHandler\Extractor;

use Generator;
use Micro\Plugin\Http\Business\Handler\RequestHandlerInterface;
use Micro\Plugin\Http\Handler\HandlerAbstractFactoryInterface;
use Micro\Plugin\Http\Handler\Response\ResponseHandlerInterface;
use Symfony\Component\Routing\Route;

class RouteHandlerExtractor implements RouteHandlerExtractorInterface
{
    public const OPT_ROUTE_HANDLER = 'handler';
    public const KEY_HANDLER_REQUEST = 'request';
    public const KEY_HANDLER_RESPONSE = 'response';

    public function __construct(private readonly HandlerAbstractFactoryInterface $handlerAbstractFactory)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function extractRequestHandlers(Route $route): iterable
    {
        return $this->extractHandlers($route, self::KEY_HANDLER_REQUEST);
    }

    /**
     * {@inheritDoc}
     */
    public function extractResponseHandlers(Route $route): iterable
    {
        return $this->extractHandlers($route, self::KEY_HANDLER_RESPONSE);
    }

    /**
     * @param Route $route
     * @param string $handlerType
     * @return Generator<RequestHandlerInterface|ResponseHandlerInterface>
     */
    protected function extractHandlers(Route $route, string $handlerType): Generator
    {
        foreach ($this->extractHandlersClasses($route, $handlerType) as $handler) {
            yield $this->handlerAbstractFactory->create($handler);
        }
    }

    /**
     * @param Route $route
     * @param string $handlerType
     *
     * @return array
     */
    protected function extractHandlersClasses(Route $route, string $handlerType): array
    {
        $handlers = $route->getOption('options');

        return $handlers[self::OPT_ROUTE_HANDLER][$handlerType] ?? [];
    }
}