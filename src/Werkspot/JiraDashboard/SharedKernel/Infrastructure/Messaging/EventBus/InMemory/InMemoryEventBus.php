<?php
declare(strict_types=1);

namespace Werkspot\SharedKernel\Infrastructure\Messaging\EventBus\InMemory;

use League\Event\EmitterInterface;
use League\Event\EventInterface;
use League\Event\GeneratorInterface;
use League\Event\ListenerInterface;
use League\Event\ListenerProviderInterface;

final class InMemoryEventBus implements EmitterInterface
{
    /** @var ListenerInterface[] */
    private $listeners = [];

    public function removeListener($event, $listener)
    {
        $index = 0;
        foreach ($this->listeners[$this->getEventName($event)] as $listenerCallback) {
            if ($listenerCallback === $listener) {
                unset($this->listeners[$this->getEventName($event)][$index]);
            }
            $index++;
        }

        return $this;
    }

    public function useListenerProvider(ListenerProviderInterface $provider)
    {
        // TODO
        return $this;
    }

    public function removeAllListeners($event)
    {
        $this->listeners[$this->getEventName($event)] = [];

        return $this;
    }

    public function hasListeners($event)
    {
        return !empty($this->listeners[$this->getEventName($event)]);
    }

    public function getListeners($event)
    {
        return $this->listeners[$this->getEventName($event)];
    }

    public function emit($event)
    {
        if (array_key_exists($this->getEventName($event), $this->listeners)) {
            /** @var ListenerInterface $listener */
            foreach ($this->listeners[$this->getEventName($event)] as $listener) {
                $listener->handle($event);
            }
        }

        return $event;
    }

    public function emitBatch(array $events)
    {
        // TODO
        return $this;
    }

    public function emitGeneratedEvents(GeneratorInterface $generator): array
    {
        // TODO
        return $this;
    }

    /**
     * @param array $listeners
     */
    public function setListeners(array $listeners): void
    {
        $this->listeners = $listeners;
    }

    public function addListener($event, $listener, $priority = self::P_NORMAL)
    {
        if (!array_key_exists($this->getEventName($event), $this->listeners)) {
            $this->listeners[$this->getEventName($event)] = [$listener];
        } else {
            $this->listeners[$this->getEventName($event)][] = $listener;
        }

        return $this;
    }

    public function addOneTimeListener($event, $listener, $priority = self::P_NORMAL)
    {
        // TODO
        return $this;
    }

    /**
     * @param EventInterface|string $event
     * @return string
     */
    private function getEventName($event): string
    {
        $eventName = is_string($event) ? $event : $event->getName();

        return $eventName;
    }
}
