<?php

namespace Micro\Plugin\Http\Exception;

use Throwable;

class BadConfigurationHttpException extends HttpException
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}