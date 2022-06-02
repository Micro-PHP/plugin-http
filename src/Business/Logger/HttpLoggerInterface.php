<?php

namespace Micro\Plugin\Http\Business\Logger;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface HttpLoggerInterface
{
    /**
     * @param Request $request
     * @param Response $response
     * @param string|null $message
     * @param array $context
     *
     * @return void
     */
    public function warning(Request $request, Response $response, string $message = null, array $context = []): void;

    /**
     * @param \Throwable $e
     * @param Request $request
     * @param Response $response
     * @param array $context
     *
     * @return void
     */
    public function error(\Throwable $e, Request $request, Response $response, array $context = []): void;

    /**
     * @param Request $request
     * @param Response $response
     * @param array $context
     *
     * @return void
     */
    public function access(Request $request, Response $response, array $context = []): void;
}