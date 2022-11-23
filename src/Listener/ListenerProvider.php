<?php

namespace Micro\Plugin\Http\Listener;

use Micro\Component\EventEmitter\EventListenerInterface;
use Micro\Component\EventEmitter\Impl\Provider\AbstractListenerProvider;

class ListenerProvider extends AbstractListenerProvider
{
    /**
     * @var iterable|EventListenerInterface[]
     */
    private readonly iterable $eventListenerCollection;

    /**
     * @param EventListenerInterface ...$eventListenerCollection
     */
    public function __construct(EventListenerInterface ...$eventListenerCollection)
    {
        $this->eventListenerCollection = $eventListenerCollection;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventListeners(): iterable
    {
        return $this->eventListenerCollection;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'http-plugin';
    }
}