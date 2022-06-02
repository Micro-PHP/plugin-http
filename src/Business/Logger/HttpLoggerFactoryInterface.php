<?php

namespace Micro\Plugin\Http\Business\Logger;

interface HttpLoggerFactoryInterface
{
    /**
     * @return HttpLoggerInterface
     */
    public function create(): HttpLoggerInterface;
}