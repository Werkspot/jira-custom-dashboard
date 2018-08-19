<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\AchievedSprintsWidget\Integration;

use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\SetSprintAsAchievedCommand;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class SetSprintAsAchievedCommandTest extends IntegrationTestAbstract
{
    private const ACHIEVED = true;

    /**
     * @test
     */
    public function setSprintAsAchieved_whenDataIsValid_shouldSetThatSprintAsAchieved()
    {
        $allTeams = $this->teamRepositoryDoctrineAdapter->findAll();
        $teamId = $allTeams[0]->getId();

        $sprint = $this->sprintRepositoryDoctrineAdapter->findActiveByTeam($teamId);

        $this->assertEquals(false, $sprint->isAchieved());

        $setSprintAsAchievedCommand = new SetSprintAsAchievedCommand($sprint->getId(), self::ACHIEVED);
        $this->commandBus->handle($setSprintAsAchievedCommand);

        $this->assertEquals(true, $sprint->isAchieved());
    }
}
