<?php

namespace Micro\Plugin\Http\Exception;

class HttpAccessDeniedException extends HttpException
{
    /**
     * @param mixed $message
     *
     * @param \Throwable|null $previous
     */
    public function __construct(mixed $message = 'Authentication error', ?\Throwable $previous = null)
    {
        parent::__construct($message, 401, $previous);
    }
}