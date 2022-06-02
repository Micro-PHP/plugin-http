<?php

namespace Micro\Plugin\Http\Exception;

use \Throwable;

class RouteNotFoundHttpException extends HttpException
{
    /**
     * @param string $message
     * @param string $method
     *
     * @param Throwable|null $previous
     */
    public function __construct(string $message, string $method, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}