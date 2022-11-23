<?php

namespace Micro\Plugin\Http\Exception;

class HttpException extends \Exception
{
    /**
     * @param mixed $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(mixed $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}