<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\AchievedSprintsWidget\Integration;

use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\GetAchievedSprintsQuery;
use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\SetSprintAsAchievedCommand;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class GetAchievedSprintsQueryTest extends IntegrationTestAbstract
{
    private const ACHIEVED = true;

    /**
     * @test
     */
    public function getAchievedSprints_whenAtLeastOneSprintHasBeenAchieved_shouldReturnAListOfAchievedSprints()
    {
        $allTeams = $this->teamRepositoryDoctrineAdapter->findAll();
        $teamId = $allTeams[0]->getId();

        $getAchievedSprintsQuery = new GetAchievedSprintsQuery($teamId->id());
        $achievedSprints = $this->commandBus->handle($getAchievedSprintsQuery);

        $this->assertEquals(0, $achievedSprints['achieved']->number());

        $sprint = $this->sprintRepositoryDoctrineAdapter->findActiveByTeam($teamId);

        $setSprintAsAchievedCommand = new SetSprintAsAchievedCommand($sprint->getId(), self::ACHIEVED);
        $this->commandBus->handle($setSprintAsAchievedCommand);

        $achievedSprints = $this->commandBus->handle($getAchievedSprintsQuery);

        $this->assertEquals(1, $achievedSprints['achieved']->number());
        $this->assertEquals(true, $sprint->isAchieved());
    }
}
