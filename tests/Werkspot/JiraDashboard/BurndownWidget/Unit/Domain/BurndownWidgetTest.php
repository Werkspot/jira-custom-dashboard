<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\BurndownWidget\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Werkspot\JiraDashboard\BurndownWidget\Domain\BurndownWidget;
use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPoints;
use Werkspot\JiraDashboard\BurndownWidget\Infrastructure\Persistence\InMemory\RemainingPoints\BugCounterRepositoryInMemoryAdapter;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Sprint\SprintRepositoryInMemoryAdapter;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Team\TeamRepositoryInMemoryAdapter;

class BurndownWidgetTest extends TestCase
{
    /**
     * @test
     */
    public function getRemainingPointsBySprint_whenThereIsASprint_shouldReturnRemainingPointsCollectionOrderedByDate()
    {
        $teamRepository = $this->getTeamRepository();
        $teams = $teamRepository->findAll();
        $team = array_shift($teams);

        $sprintRepository = $this->getSprintRepository($team);
        $activeSprint = $sprintRepository->findActiveByTeam($team->getId());

        $remainingPointsRepository = $this->getRemainingPointsRepository($activeSprint);

        $burndownWidget = new BurndownWidget($sprintRepository, $remainingPointsRepository);
        $remainingPointsCollection = $burndownWidget->getRemainingPointsBySprint($activeSprint);

        $this->assertCount(10, $remainingPointsCollection);
        $this->assertInstanceOf(RemainingPoints::class, $remainingPointsCollection[0]);
        $this->assertTrue(($remainingPointsCollection[0])->getDate() < ($remainingPointsCollection[1])->getDate());
    }

    /**
     * @test
     */
    public function saveNewRemainingPoints_whenDataIsValid_shouldSaveNewRemainingStoryPointsDataToPersistence()
    {
        $teamRepository = $this->getTeamRepository();
        $teams = $teamRepository->findAll();
        $team = array_shift($teams);

        $sprintRepository = $this->getSprintRepository($team);
        $activeSprint = $sprintRepository->findActiveByTeam($team->getId());

        $remainingPointsRepository = $this->getRemainingPointsRepository($activeSprint);

        $today = new \DateTimeImmutable('today');

        $newRemainingPointsValue = PositiveNumber::create(0);
        $newRemainingPoints = new RemainingPoints($activeSprint, $today, $newRemainingPointsValue);

        $burndownWidget = new BurndownWidget($sprintRepository, $remainingPointsRepository);
        $burndownWidget->saveRemainingPoints($newRemainingPoints);

        $remainingPointsCollection = $remainingPointsRepository->findBySprint($activeSprint);

        $this->assertCount(11, $remainingPointsCollection);
        $this->assertEquals($newRemainingPointsValue, ($remainingPointsCollection[10])->getValue());
    }

    /**
     * @test
     */
    public function saveRemainingPoints_whenDateAlreadyExists_shouldUpdateRemainingPointsData()
    {
        $teamRepository = $this->getTeamRepository();
        $teams = $teamRepository->findAll();
        $team = array_shift($teams);

        $sprintRepository = $this->getSprintRepository($team);
        $activeSprint = $sprintRepository->findActiveByTeam($team->getId());

        $remainingPointsRepository = $this->getRemainingPointsRepository($activeSprint);

        $today = new \DateTimeImmutable('today - 1 days');

        $updatedRemainingPoints = new RemainingPoints($activeSprint, $today, PositiveNumber::create(0));

        $burndownWidget = new BurndownWidget($sprintRepository, $remainingPointsRepository);
        $burndownWidget->saveRemainingPoints($updatedRemainingPoints);

        $savedRemainingPoints = $remainingPointsRepository->findBySprint($activeSprint);

        $this->assertEquals($updatedRemainingPoints, $savedRemainingPoints[9]);
    }

    private function getTeamRepository(): TeamRepositoryInMemoryAdapter
    {
        $teamRepository = new TeamRepositoryInMemoryAdapter([
            new Team(
                Id::create(),
                ShortText::create('Team 1')
            ),
        ]);

        return $teamRepository;
    }

    private function getSprintRepository(Team $team): SprintRepositoryInMemoryAdapter
    {
        $sprintRepository = new SprintRepositoryInMemoryAdapter([
            new Sprint(
                Id::create(),
                ShortText::create('Sprint title'),
                $team,
                new \DateTimeImmutable('today - 10 days'),
                new \DateTimeImmutable('today'),
                PositiveNumber::create(1)
            ),
        ]);

        return $sprintRepository;
    }

    /**
     * @throws \Exception
     */
    private function getRemainingPointsRepository(Sprint $sprint): BugCounterRepositoryInMemoryAdapter
    {
        $remainingPointsRepository = new BugCounterRepositoryInMemoryAdapter([
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 10 days'), PositiveNumber::create(30)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 9 days'), PositiveNumber::create(25)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 8 days'), PositiveNumber::create(24)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 7 days'), PositiveNumber::create(19)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 6 days'), PositiveNumber::create(10)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 5 days'), PositiveNumber::create(9)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 4 days'), PositiveNumber::create(9)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 3 days'), PositiveNumber::create(8)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 2 days'), PositiveNumber::create(5)),
            new RemainingPoints($sprint, new \DateTimeImmutable('today - 1 days'), PositiveNumber::create(3)),
        ]);

        return $remainingPointsRepository;
    }
}
