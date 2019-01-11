<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\BugCounterWidget\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Werkspot\JiraDashboard\BugCounterWidget\Domain\BugCounter;
use Werkspot\JiraDashboard\BugCounterWidget\Domain\BugCounterWidget;
use Werkspot\JiraDashboard\BugCounterWidget\Infrastructure\Persistence\InMemory\BugCounter\BugCounterRepositoryInMemoryAdapter;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Sprint\SprintRepositoryInMemoryAdapter;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Team\TeamRepositoryInMemoryAdapter;
use Werkspot\JiraDashboard\SprintWidget\Domain\SprintWidget;

class BugCounterWidgetTest extends TestCase
{
//    /**
//     * @test
//     */
//    public function createSprint_shouldAddBugCounterBySprint()
//    {
//        $team = $this->getTeam();
//
//        $this->addSprintUsingWidget($team);
//
//        $bugCounterRepository = $this->getBugCounterRepository();
//
//        $bugCounterWidget = new BugCounterWidget($bugCounterRepository);
//        $bugCollection = $bugCounterWidget->getBugsCreatedByTeam($team);
//
//        $this->assertCount(1, $bugCollection);
//    }

    /**
     * @test
     */
    public function getBugCounterByTeam_whenThereAreNoBugEntries_shouldReturnEmptyCollection()
    {
        $team = $this->getTeam();
        $bugCounterRepository = $this->getBugCounterRepository();

        $bugCounterWidget = new BugCounterWidget($bugCounterRepository);

        $bugCollection = $bugCounterWidget->getBugsCreatedByTeam($team);

        $this->assertEmpty($bugCollection);
    }

    /**
     * @test
     */
    public function getBugCounterByTeam_whenThereAreBugEntries_shouldReturnBugCounterCollection()
    {
        $team = $this->getTeam();
        $sprint = $this->getSprintRepository($team)->findActiveByTeam($team->getId());

        $bugCounter = new BugCounter(
            $sprint,
            PositiveNumber::create(1),
            PositiveNumber::create(1),
            PositiveNumber::create(0)
        );
        $bugCounterRepository = $this->getBugCounterRepository([$bugCounter]);

        $bugCounterWidget = new BugCounterWidget($bugCounterRepository);

        $bugCollection = $bugCounterWidget->getBugsCreatedByTeam($team);

        $this->assertCount(1, $bugCollection);
    }

    /**
     * @test
     */
    public function addBug_whenSprintHasNotBugsYet_shouldCreateANewBugCounterForSprint()
    {
        $team = $this->getTeam();
        $sprint = $this->getSprint($team);

        $bugCounter = new BugCounter(
            $sprint,
            PositiveNumber::create(0),
            PositiveNumber::create(0),
            PositiveNumber::create(0)
        );
        $bugCounterRepository = $this->getBugCounterRepository([$bugCounter]);

        $bugCounterWidget = new BugCounterWidget($bugCounterRepository);
        $bugCounterWidget->addBugToSprint($sprint);

        /** @var BugCounter[] $bugsCollection */
        $bugsCollection = $bugCounterWidget->getBugsCreatedByTeam($team);
        $bugCounter = $bugsCollection[0];

        $this->assertEquals(1, $bugCounter->getBugsCreatedCounter()->number());
    }

    /**
     * @test
     */
    public function addBug_whenSprintHasBugsAlready_shouldIncreaseBugCreatedCounterAndIncreaseTotalBugsForSprint()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function fixBug_whenSprintHasBugs_shouldIncreaseBugFixedCounterAndDecreaseTotalBugsForSprint()
    {
        $this->assertTrue(true);
    }

    private function getTeam(): Team
    {
        $teamRepository = $this->getTeamRepository();
        $teams = $teamRepository->findAll();
        $team = array_shift($teams);

        return $team;
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

    private function getSprint(Team $team): Sprint
    {
        $sprintRepository = $this->getSprintRepository($team);
        $sprints = $sprintRepository->findAll();
        $sprint = array_shift($sprints);

        return $sprint;
    }

    private function getSprintRepository(Team $team): SprintRepositoryInMemoryAdapter
    {
        $sprintRepository = new SprintRepositoryInMemoryAdapter([
            new Sprint(
                Id::create(),
                ShortText::create('Sprint title'),
                $team,
                new \DateTimeImmutable('today - 4 days'),
                new \DateTimeImmutable('today + 4 days'),
                PositiveNumber::create(1)
            ),
        ]);

        return $sprintRepository;
    }

    /**
     * @param BugCounter[] $bugCounterArray
     */
    private function getBugCounterRepository(array $bugCounterArray = []): BugCounterRepositoryInMemoryAdapter
    {
        $bugCounterRepository = new BugCounterRepositoryInMemoryAdapter($bugCounterArray);

        return $bugCounterRepository;
    }

    /**
     * @param $team
     * @throws \Exception
     */
    private function addSprintUsingWidget($team): void
    {
        $sprintStartDate = new \DateTimeImmutable('today + 5 days');
        $sprintEndDate = new \DateTimeImmutable('today + 9 days');

        $this->sprintRepository = new SprintRepositoryInMemoryAdapter([]);
        $newSprintNumber = $this->sprintRepository->getNextSprintNumberByTeam($team);

        $sprint = new Sprint(
            Id::create(),
            ShortText::create("Sprint $newSprintNumber"),
            $team,
            $sprintStartDate,
            $sprintEndDate,
            PositiveNumber::create($newSprintNumber)
        );

        $sprintWidget = new SprintWidget($this->sprintRepository);
        $sprintWidget->addNewSprint($sprint);
    }
}
