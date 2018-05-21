<?php
declare(strict_types=1);

namespace Werkspot\SharedKernel\Infrastructure\Messaging\CommandBus\Tactician;

use League\Event\EmitterInterface;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;

final class TacticianCommandBusFactory
{
    /** @var CommandBus */
    private $commandBus;

    /** @var EmitterInterface */
    private $eventBus;

    public function __construct(
//        RegisterUserService $registerUserService,
        EmitterInterface $eventBus
    ) {
        $this->eventBus = $eventBus;

        $nameExtractor = new ClassNameExtractor();

        $inflector = new HandleInflector();

        // register commands
//        $registerUserCommandHandler = new RegisterUserCommandHandler($registerUserService, $this->eventBus);

        $locator = new InMemoryLocator();
//        $locator->addHandler($registerUserCommandHandler, RegisterUserCommand::class);

        $commandHandlerMiddleware = new CommandHandlerMiddleware($nameExtractor, $locator, $inflector);

        $this->commandBus = new CommandBus([$commandHandlerMiddleware]);
    }

    /**
     * @return CommandBus
     */
    public function create(): CommandBus
    {
        return $this->commandBus;
    }
}
