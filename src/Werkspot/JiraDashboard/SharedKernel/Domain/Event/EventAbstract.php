<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\Event;

use League\Event\EmitterInterface;
use League\Event\EventInterface;

abstract class EventAbstract implements EventInterface
{
    /** @var string */
    private $id = '';

    /** @var bool */
    private $propagationActive = true;

    /** @var EmitterInterface */
    private $emitter;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function setEmitter(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;

        return $this;
    }

    public function getEmitter()
    {
        return $this->emitter;
    }

    public function stopPropagation()
    {
        $this->propagationActive = false;

        return $this;
    }

    public function isPropagationStopped()
    {
        return !$this->propagationActive;
    }

    abstract public function getName();
}
