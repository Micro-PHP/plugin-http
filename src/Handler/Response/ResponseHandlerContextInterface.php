<?php

namespace Micro\Plugin\Http\Handler\Response;

use Micro\Plugin\Http\Handler\HandlerContextInterface;
use Throwable;

interface ResponseHandlerContextInterface extends HandlerContextInterface
{
    /**
     * @param mixed $response
     *
     * @return void
     */
    public function setResponse(mixed $response): void;

    /**
     * @return mixed
     */
    public function getResponse(): mixed;

    /**
     * @return Throwable|null
     */
    public function getException(): ?Throwable;
}