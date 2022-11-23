<?php

namespace Micro\Plugin\Http\Exception;

class HttpUnprocessableEntityException extends HttpException
{
    /**
     * @param string $message
     *
     * @param \Throwable|null $throwable
     */
    public function __construct(string $message = 'Unprocessable entity', \Throwable $throwable = null)
    {
        parent::__construct($message, 422, $throwable);
    }
}