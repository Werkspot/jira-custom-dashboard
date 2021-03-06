<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SharedKernel\Integration;

use League\Event\EmitterInterface;
use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPointsRepositoryInterface;
use Werkspot\JiraDashboard\BurndownWidget\Infrastructure\Persistence\Doctrine\RemainingPoints\RemainingPointsRepositoryDoctrineAdapter;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceRepositoryInterface;
use Werkspot\JiraDashboard\ConfidenceWidget\Infrastructure\Persistence\Doctrine\Confidence\ConfidenceRepositoryDoctrineAdapter;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Messaging\CommandBus\Tactician\TacticianCommandBusFactory;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Messaging\EventBus\League\LeagueEventBusFactory;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\Sprint\SprintRepositoryDoctrineAdapter;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\Team\TeamRepositoryDoctrineAdapter;
use Werkspot\Tests\JiraDashboard\SharedKernel\DoctrineAwareTestTrait;

class IntegrationTestAbstract extends TestCase
{
    use DoctrineAwareTestTrait;

    /**
     * @var TeamRepositoryDoctrineAdapter
     */
    protected $teamRepositoryDoctrineAdapter;

    /**
     * @var SprintRepositoryInterface
     */
    protected $sprintRepositoryDoctrineAdapter;

    /**
     * @var ConfidenceRepositoryInterface
     */
    protected $confidenceRepositoryDoctrineAdapter;

    /**
     * @var RemainingPointsRepositoryInterface
     */
    protected $remainingPointsRepositoryDoctrineAdapter;

    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * @var EmitterInterface
     */
    protected $eventBus;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpEntityManager();

        $this->fixturesLoader();

        $this->teamRepositoryDoctrineAdapter = new TeamRepositoryDoctrineAdapter($this->entityManager);
        $this->sprintRepositoryDoctrineAdapter = new SprintRepositoryDoctrineAdapter($this->entityManager);
        $this->confidenceRepositoryDoctrineAdapter = new ConfidenceRepositoryDoctrineAdapter($this->entityManager);
        $this->remainingPointsRepositoryDoctrineAdapter = new RemainingPointsRepositoryDoctrineAdapter($this->entityManager);

        $this->setupEventBus();

        $this->setupCommandBus();
    }

    private function setupEventBus(): void
    {
        $eventBusFactory = new LeagueEventBusFactory();

        $this->eventBus = $eventBusFactory->create();
    }

    private function setupCommandBus(): void
    {
        $commandBusFactory = new TacticianCommandBusFactory(
            $this->teamRepositoryDoctrineAdapter,
            $this->sprintRepositoryDoctrineAdapter,
            $this->confidenceRepositoryDoctrineAdapter,
            $this->remainingPointsRepositoryDoctrineAdapter,
            $this->eventBus
        );

        $this->commandBus = $commandBusFactory->create();
    }
}
