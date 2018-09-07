<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\TeamWidget\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Sprint\SprintRepositoryInMemoryAdapter;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Team\TeamRepositoryInMemoryAdapter;
use Werkspot\JiraDashboard\TeamWidget\Domain\TeamWidget;

class TeamWidgetTest extends TestCase
{
    /**
     * @test
     */
    public function getTeams_whenThereIsNoTeams_shouldReturnAnEmptyArray()
    {
        $teamRepository = new TeamRepositoryInMemoryAdapter([]);
        $sprintRepository = new SprintRepositoryInMemoryAdapter([]);

        $teamWidget = new TeamWidget($teamRepository, $sprintRepository);

        self::assertEmpty($teamWidget->getTeams());
    }

    /**
     * @test
     */
    public function getTeams_whenThereAreTeams_shouldReturnTeamCollection()
    {
        $team1 = new Team(
            Id::create(),
            ShortText::create('Team 1')
        );
        $team2 = new Team(
            Id::create(),
            ShortText::create('Team 2')
        );

        $teamRepository = new TeamRepositoryInMemoryAdapter([
            $team1,
            $team2,
        ]);

        $sprintRepository = new SprintRepositoryInMemoryAdapter([]);

        $teamWidget = new TeamWidget($teamRepository, $sprintRepository);
        $teams = $teamWidget->getTeams();

        self::assertCount(2, $teams);
        self::assertSame($team1, array_shift($teams));
        self::assertSame($team2, array_shift($teams));
    }

    /**
     * @test
     */
    public function addTeam_whenThereIsNoTeams_shouldSaveNewTeamToPersistence()
    {
        $teamRepository = new TeamRepositoryInMemoryAdapter([]);

        $team = new Team(
            Id::create(),
            ShortText::create('Team name')
        );

        $sprintRepository = new SprintRepositoryInMemoryAdapter([]);

        $teamWidget = new TeamWidget($teamRepository, $sprintRepository);
        $teamWidget->addTeam($team);

        self::assertCount(1, $teamRepository->findAll());
        self::assertSame($team, $teamRepository->find($team->getId()));
    }

    /**
     * @test
     */
    public function addTeam_whenThereAreSome_shouldSaveNewTeamToPersistence()
    {
        $team1 = new Team(
            Id::create(),
            ShortText::create('Team 1')
        );
        $team2 = new Team(
            Id::create(),
            ShortText::create('Team 2')
        );

        $teamRepository = new TeamRepositoryInMemoryAdapter([
            $team1,
            $team2,
        ]);

        $team = new Team(
            Id::create(),
            ShortText::create('Team name')
        );

        $sprintRepository = new SprintRepositoryInMemoryAdapter([]);

        $teamWidget = new TeamWidget($teamRepository, $sprintRepository);
        $teamWidget->addTeam($team);

        self::assertCount(3, $teamRepository->findAll());
        self::assertSame($team, $teamRepository->find($team->getId()));
    }

    /**
     * @test
     */
    public function addTeam_whenTeamAlreadyExists_shouldUpdateTeamIntoPersistence()
    {
        $team = new Team(
            Id::create(),
            ShortText::create('Team name')
        );

        $teamRepository = new TeamRepositoryInMemoryAdapter([]);

        $sprintRepository = new SprintRepositoryInMemoryAdapter([]);

        $teamWidget = new TeamWidget($teamRepository, $sprintRepository);
        $teamWidget->addTeam($team);

        self::assertEquals($team->getName(), $teamRepository->find($team->getId())->getName());

        $team->setName(ShortText::create('New Name'));
        $teamWidget->addTeam($team);

        self::assertEquals($team->getName(), $teamRepository->find($team->getId())->getName());
    }

    /**
     * @test
     */
    public function getSprints_whenThereIsNoSprints_shouldReturnAnEmptyCollection()
    {
        $team = new Team(
            Id::create(),
            ShortText::create('Team name')
        );

        $teamRepository = new TeamRepositoryInMemoryAdapter([$team]);

        $sprintRepository = new SprintRepositoryInMemoryAdapter([]);

        $teamWidget = new TeamWidget($teamRepository, $sprintRepository);
        $sprintCollection = $teamWidget->getSprints($team);

        $this->assertEquals([], $sprintCollection);
    }

    /**
     * @test
     */
    public function getSprints_whenThereAreSomeSprints_shouldReturnASprintCollection()
    {
        $team = new Team(
            Id::create(),
            ShortText::create('Team name')
        );

        $sprint = new Sprint(
            Id::create(),
            ShortText::create('One title'),
            $team,
            new \DateTimeImmutable("now - 5 days"),
            new \DateTimeImmutable("now + 5 days"),
            PositiveNumber::create(0)
        );

        $teamRepository = new TeamRepositoryInMemoryAdapter([$team]);

        $sprintRepository = new SprintRepositoryInMemoryAdapter([$sprint]);

        $teamWidget = new TeamWidget($teamRepository, $sprintRepository);
        $sprintCollection = $teamWidget->getSprints($team);

        $this->assertEquals([$sprint], $sprintCollection);
    }
}
