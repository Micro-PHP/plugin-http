<?php

namespace Micro\Plugin\Http\Exception;

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class HttpException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}