<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Messaging\CommandBus\Tactician;

use League\Event\EmitterInterface;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Messaging\CommandBus\Tactician\GraphQLMiddleware;
use Werkspot\JiraDashboard\AchievedSprintsWidget\Application\GetAchievedSprintsQueryHandler;
use Werkspot\JiraDashboard\AchievedSprintsWidget\Application\SetSprintAsAchievedCommandHandler;
use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\GetAchievedSprintsQuery;
use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\SetSprintAsAchievedCommand;
use Werkspot\JiraDashboard\BurndownWidget\Application\RemainingPoints\GetRemainingPointsBySprintQueryHandler;
use Werkspot\JiraDashboard\BurndownWidget\Application\RemainingPoints\SaveRemainingPointsCommandHandler;
use Werkspot\JiraDashboard\BurndownWidget\Domain\GetRemainingPointsBySprintQuery;
use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPointsRepositoryInterface;
use Werkspot\JiraDashboard\BurndownWidget\Domain\SaveRemainingPointsCommand;
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

    /**
     * @var RemainingPointsRepositoryInterface
     */
    private $remainingPointsRepository;

    public function __construct(
        SprintRepositoryInterface $sprintRepository,
        ConfidenceRepositoryInterface $confidenceRepository,
        RemainingPointsRepositoryInterface $remainingPointsRepository,
        EmitterInterface $eventBus
    ) {
        $this->sprintRepository = $sprintRepository;
        $this->confidenceRepository = $confidenceRepository;
        $this->remainingPointsRepository = $remainingPointsRepository;
        $this->eventBus = $eventBus;

        $nameExtractor = new ClassNameExtractor();

        $inflector = new HandleInflector();

        // register commands/queries
        $getConfidenceBySprintQueryHandler = new GetConfidenceBySprintQueryHandler($this->sprintRepository, $this->confidenceRepository);
        $saveConfidenceCommandHandler = new SaveConfidenceCommandHandler($this->sprintRepository, $this->confidenceRepository);

        $addNewSprintCommandHandler = new AddNewSprintCommandHandler($this->sprintRepository);
        $getActiveSprintQueryHandler = new GetActiveSprintQueryHandler($this->sprintRepository);

        $getAchievedSprintsQueryHandler = new GetAchievedSprintsQueryHandler($this->sprintRepository);
        $setSprintAsAchievedCommandHandler = new SetSprintAsAchievedCommandHandler($this->sprintRepository);

        $getRemainingPointsBySprintQueryHandler = new GetRemainingPointsBySprintQueryHandler($this->sprintRepository, $this->remainingPointsRepository);
        $saveRemainingPointsCommandHandler = new SaveRemainingPointsCommandHandler($this->sprintRepository, $this->remainingPointsRepository);

        $locator = new InMemoryLocator();
        $locator->addHandler($getConfidenceBySprintQueryHandler, GetConfidenceBySprintQuery::class);
        $locator->addHandler($saveConfidenceCommandHandler, SaveConfidenceCommand::class);

        $locator->addHandler($addNewSprintCommandHandler, AddNewSprintCommand::class);
        $locator->addHandler($getActiveSprintQueryHandler, GetActiveSprintQuery::class);

        $locator->addHandler($getAchievedSprintsQueryHandler, GetAchievedSprintsQuery::class);
        $locator->addHandler($setSprintAsAchievedCommandHandler, SetSprintAsAchievedCommand::class);

        $locator->addHandler($getRemainingPointsBySprintQueryHandler, GetRemainingPointsBySprintQuery::class);
        $locator->addHandler($saveRemainingPointsCommandHandler, SaveRemainingPointsCommand::class);

        $graphqlMiddleware = new GraphQLMiddleware();
        $commandHandlerMiddleware = new CommandHandlerMiddleware($nameExtractor, $locator, $inflector);

        $this->commandBus = new CommandBus([$graphqlMiddleware, $commandHandlerMiddleware]);
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
