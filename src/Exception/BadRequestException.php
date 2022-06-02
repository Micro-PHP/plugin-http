<?php

namespace Micro\Plugin\Http\Exception;

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class BadRequestException extends HttpException
{
    public function __construct(string $message = "", ?\Throwable $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }
}