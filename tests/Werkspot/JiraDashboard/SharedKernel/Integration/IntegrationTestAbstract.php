<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SharedKernel\Integration;

use League\Event\EmitterInterface;
use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceRepositoryInterface;
use Werkspot\JiraDashboard\ConfidenceWidget\Infrastructure\Persistence\Doctrine\Confidence\ConfidenceRepositoryDoctrineAdapter;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Messaging\CommandBus\Tactician\TacticianCommandBusFactory;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Messaging\EventBus\League\LeagueEventBusFactory;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\Doctrine\Sprint\SprintRepositoryDoctrineAdapter;
use Werkspot\Tests\JiraDashboard\SharedKernel\DoctrineAwareTestTrait;

class IntegrationTestAbstract extends TestCase
{
    use DoctrineAwareTestTrait;

    /**
     * @var ConfidenceRepositoryInterface
     */
    protected $confidenceRepositoryDoctrineAdapter;

    /**
     * @var SprintRepositoryInterface
     */
    protected $sprintRepositoryDoctrineAdapter;

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

        $this->sprintRepositoryDoctrineAdapter = new SprintRepositoryDoctrineAdapter($this->entityManager);
        $this->confidenceRepositoryDoctrineAdapter = new ConfidenceRepositoryDoctrineAdapter($this->entityManager);

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
            $this->sprintRepositoryDoctrineAdapter,
            $this->confidenceRepositoryDoctrineAdapter,
            $this->eventBus
        );

        $this->commandBus = $commandBusFactory->create();
    }
}
