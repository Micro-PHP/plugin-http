<?php

namespace Micro\Plugin\Http\Exception;

class HttpNotFoundException extends HttpException
{
    public function __construct(string $message = "", ?\Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}