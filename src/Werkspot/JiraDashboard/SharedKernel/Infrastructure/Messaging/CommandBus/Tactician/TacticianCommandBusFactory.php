<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Messaging\CommandBus\Tactician;

use League\Event\EmitterInterface;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Werkspot\JiraDashboard\ConfidenceWidget\Application\Confidence\GetConfidenceBySprintQueryHandler;
use Werkspot\JiraDashboard\ConfidenceWidget\Application\Confidence\SaveConfidenceCommandHandler;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceRepositoryInterface;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\GetConfidenceBySprintQuery;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\SaveConfidenceCommand;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SprintWidget\Application\AddNewSprintCommandHandler;
use Werkspot\JiraDashboard\SprintWidget\Application\GetActiveSprintQueryHandler;
use Werkspot\JiraDashboard\SprintWidget\Domain\AddNewSprintCommand;
use Werkspot\JiraDashboard\SprintWidget\Domain\GetActiveSprintQuery;

final class TacticianCommandBusFactory
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    /**
     * @var ConfidenceRepositoryInterface
     */
    private $confidenceRepository;

    /**
     * @var EmitterInterface
     */
    private $eventBus;

    public function __construct(
        SprintRepositoryInterface $sprintRepository,
        ConfidenceRepositoryInterface $confidenceRepository,
        EmitterInterface $eventBus
    ) {
        $this->sprintRepository = $sprintRepository;
        $this->confidenceRepository = $confidenceRepository;
        $this->eventBus = $eventBus;

        $nameExtractor = new ClassNameExtractor();

        $inflector = new HandleInflector();

        // register commands/queries
        $getConfidenceBySprintQueryHandler = new GetConfidenceBySprintQueryHandler($this->sprintRepository, $this->confidenceRepository);
        $saveConfidenceCommandHandler = new SaveConfidenceCommandHandler($this->sprintRepository, $this->confidenceRepository);
        $addNewSprintCommandHandler = new AddNewSprintCommandHandler($this->sprintRepository);
        $getActiveSprintQueryHandler = new GetActiveSprintQueryHandler($this->sprintRepository);

        $locator = new InMemoryLocator();
        $locator->addHandler($getConfidenceBySprintQueryHandler, GetConfidenceBySprintQuery::class);
        $locator->addHandler($saveConfidenceCommandHandler, SaveConfidenceCommand::class);
        $locator->addHandler($addNewSprintCommandHandler, AddNewSprintCommand::class);
        $locator->addHandler($getActiveSprintQueryHandler, GetActiveSprintQuery::class);

        $commandHandlerMiddleware = new CommandHandlerMiddleware($nameExtractor, $locator, $inflector);

        $this->commandBus = new CommandBus([$commandHandlerMiddleware]);
        $this->sprintRepository = $sprintRepository;
    }

    /**
     * @return CommandBus
     */
    public function create(): CommandBus
    {
        return $this->commandBus;
    }
}
