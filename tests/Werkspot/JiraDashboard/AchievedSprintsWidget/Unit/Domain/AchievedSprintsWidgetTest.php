<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\AchievedSprintsWidget\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\AchievedSprintsWidget;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\TeamRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Sprint\SprintRepositoryInMemoryAdapter;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Team\TeamRepositoryInMemoryAdapter;

class AchievedSprintsWidgetTest extends TestCase
{
    private const ACHIEVED = true;

    /**
     * @var TeamRepositoryInterface
     */
    private $teamRepository;

    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    /**
     * @test
     */
    public function getAchievedSprints_whenAtLeastOneSprintHasBeenAchieved_shouldReturnAListOfAchievedSprints()
    {
        $startDate = new \DateTimeImmutable('today - 4 days');
        $endDate   = new \DateTimeImmutable('today + 4 days');

        $this->populateTeamRepository();
        $teams = $this->teamRepository->findAll();
        $team = array_shift($teams);
        $this->populateSprintRepository($team, $startDate, $endDate);

        $sprint = $this->sprintRepository->findActiveByTeam($team->getId());
        $sprint->setAchieved(self::ACHIEVED);

        $achievedSprintWidget = new AchievedSprintsWidget($this->sprintRepository);
        $achievedSprints = $achievedSprintWidget->getAchievedSprints($team->getId());

        $this->assertEquals(1, $achievedSprints->getAchieved()->number());
        $this->assertEquals(true, $sprint->isAchieved());
    }

    /**
     * @test
     * @expectedException \Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException
     */
    public function getAchievedSprints_whenNoneOfTheSprintsHasBeenAchieved_shouldThrowAnException()
    {
        $startDate = new \DateTimeImmutable('today - 4 days');
        $endDate   = new \DateTimeImmutable('today + 4 days');

        $this->populateTeamRepository();
        $teams = $this->teamRepository->findAll();
        $team = array_shift($teams);
        $this->populateSprintRepository($team, $startDate, $endDate);

        $achievedSprintWidget = new AchievedSprintsWidget($this->sprintRepository);
        $achievedSprintWidget->getAchievedSprints($team->getId());
    }

    private function populateTeamRepository(): void
    {
        $this->teamRepository = new TeamRepositoryInMemoryAdapter([
            new Team(
                Id::create(),
                ShortText::create('Team 1')
            ),
        ]);
    }

    private function populateSprintRepository(Team $team, \DateTimeImmutable $startDate, \DateTimeImmutable $endDate): void
    {
        $this->sprintRepository = new SprintRepositoryInMemoryAdapter([
            new Sprint(
                Id::create(),
                ShortText::create('Sprint title 1'),
                $team,
                $startDate,
                $endDate,
                PositiveNumber::create(0)
            ),
        ]);
    }
}
