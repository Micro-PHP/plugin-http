<?php

namespace Micro\Plugin\Http\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class HttpBadRequestException extends HttpException
{
    private ConstraintViolationListInterface|null $source;

    /**
     * @param string|ConstraintViolationListInterface $message
     *
     * @param Throwable|null $previous
     */
    public function __construct(ConstraintViolationListInterface|string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message, 400, $previous);

        $this->source = ($message instanceof ConstraintViolationListInterface) ? $message:null;
    }

    /**
     * @return null|ConstraintViolationListInterface
     */
    public function getSource(): null|ConstraintViolationListInterface
    {
        return $this->source;
    }
}