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
        $getAchievedSprintsQuery = new GetAchievedSprintsQuery();
        $achievedSprintCollection = $this->commandBus->handle($getAchievedSprintsQuery);

        $this->assertCount(0, $achievedSprintCollection);

        $sprint = $this->sprintRepositoryDoctrineAdapter->findActive();

        $setSprintAsAchievedCommand = new SetSprintAsAchievedCommand($sprint->getId(), self::ACHIEVED);
        $this->commandBus->handle($setSprintAsAchievedCommand);

        $achievedSprintCollection = $this->commandBus->handle($getAchievedSprintsQuery);

        $this->assertCount(1, $achievedSprintCollection);
        $this->assertEquals(true, $sprint->isAchieved());
    }
}
