<?php
declare(strict_types=1);

namespace Werkspot\SharedKernel\Infrastructure\Messaging\EventBus\League;

//use Werkspot\Application\User\Register\UserRegisteredEventListener;
//use Werkspot\Domain\User\UserRepositoryInterface;
use League\Event\Emitter;
use League\Event\EmitterInterface;

final class LeagueEventBusFactory
{
    /** @var EmitterInterface */
    private $emitter;

    public function __construct(
//        UserRepositoryInterface $userRepository
    ) {
        $this->emitter = new Emitter();

//        $this->emitter->addListener('Event.User.Registered', new UserRegisteredEventListener($userRepository));
    }

    public function create(): EmitterInterface
    {
        return $this->emitter;
    }
}
