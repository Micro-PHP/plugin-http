<?php

namespace Micro\Plugin\Http\Listener;

use Micro\Component\EventEmitter\EventInterface;
use Micro\Component\EventEmitter\EventListenerInterface;
use Micro\Kernel\App\Business\Event\ApplicationReadyEvent;
use Micro\Plugin\Http\Facade\HttpFacadeInterface;

class ApplicationStartListener implements EventListenerInterface
{
    /**
     * @param HttpFacadeInterface $httpFacade
     */
    public function __construct(
        private HttpFacadeInterface $httpFacade
    )
    {
    }

    /**
     * @param ApplicationReadyEvent $event
     *
     * @return void
     */
    public function on(EventInterface $event): void
    {
        if(!$this->isHttp()) {
            return;
        }

        $this->httpFacade->handleRequest(
            $this->httpFacade->createRequestFromGlobals()
        );
    }

    /**
     * @return bool
     */
    protected function isHttp(): bool
    {
        return PHP_SAPI !== 'cli';
    }

    /**
     * {@inheritDoc}
     */
    public static function supports(EventInterface $event): bool
    {
        return $event instanceof ApplicationReadyEvent;
    }
}