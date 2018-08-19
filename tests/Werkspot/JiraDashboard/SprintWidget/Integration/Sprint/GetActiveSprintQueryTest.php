<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SprintWidget\Integration\Sprint;

use Werkspot\JiraDashboard\SprintWidget\Domain\GetActiveSprintByTeamQuery;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class GetActiveSprintQueryTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function getActiveSprintQuery_whenThereIsAnActiveSprint_shouldReturnActiveSprintData()
    {
        $allTeams = $this->teamRepositoryDoctrineAdapter->findAll();
        $teamId = $allTeams[0]->getId();

        $today = new \DatetimeImmutable('today');

        $getActiveSprintQuery = new GetActiveSprintByTeamQuery($teamId->id());
        $activeSprint = $this->commandBus->handle($getActiveSprintQuery);

        $this->assertTrue($today >= $activeSprint->getStartDate());
        $this->assertTrue($today <= $activeSprint->getEndDate());
        $this->assertEquals('Sprint 0', $activeSprint->getTitle());
    }
}
