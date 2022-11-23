<?php

namespace Micro\Plugin\Http\Handler\Response;

use Micro\Plugin\Http\Handler\AbstractHandlerContext;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class ResponseHandlerContext extends AbstractHandlerContext implements ResponseHandlerContextInterface
{
    /**
     * @param Request $request
     * @param mixed $response
     * @param \Throwable|null $throwable
     */
    public function __construct(
        Request $request,
        private mixed $response,
        private ?\Throwable $throwable
    )
    {
        parent::__construct($request);
    }

    /**
     * {@inheritDoc}
     */
    public function getResponse(): mixed
    {
        return $this->response;
    }

    /**
     * {@inheritDoc}
     */
    public function getException(): ?Throwable
    {
        return $this->throwable;
    }

    /**
     * {@inheritDoc}
     */
    public function setResponse(mixed $response): void
    {
        $this->response = $response;
    }

    /**
     * {@inheritDoc}
     */
    public function setException(?Throwable $throwable): void
    {
        $this->throwable = $throwable;
    }
}