<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\AchievedSprintsWidget\Integration;

use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\GetAchievedSprintsQuery;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class GetAchievedSprintsQueryTest extends IntegrationTestAbstract
{
    private const ACHIEVED = true;

    /**
     * @test
     */
    public function getAchievedSprints_whenAtLeastOneSprintHasBeenAchieved_shouldReturnAListOfAchievedSprints()
    {

        $sprint = $this->sprintRepositoryDoctrineAdapter->findActive();
        $sprint->setAchieved(self::ACHIEVED);

        $getAchievedSprintsQuery = new GetAchievedSprintsQuery();
        $achievedSprintCollection = $this->commandBus->handle($getAchievedSprintsQuery);

        $this->assertCount(1, $achievedSprintCollection);
        $this->assertEquals(true, $sprint->isAchieved());
    }

    /**
     * @test
     */
    public function setAchievedSprint_COMPLETE_THIS()
    {
        $this->assertTrue(true);
    }
}
