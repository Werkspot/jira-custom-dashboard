<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Messaging\EventBus\League;

use League\Event\Emitter;
use League\Event\EmitterInterface;

final class LeagueEventBusFactory
{
    /** @var EmitterInterface */
    private $emitter;

    public function __construct()
    {
        $this->emitter = new Emitter();
    }

    public function create(): EmitterInterface
    {
        return $this->emitter;
    }
}
