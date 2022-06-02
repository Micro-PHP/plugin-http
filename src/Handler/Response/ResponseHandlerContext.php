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
        private readonly ?\Throwable $throwable
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
     * @param mixed $response
     *
     * @return void
     */
    public function setResponse(mixed $response): void
    {
        $this->response = $response;
    }
}