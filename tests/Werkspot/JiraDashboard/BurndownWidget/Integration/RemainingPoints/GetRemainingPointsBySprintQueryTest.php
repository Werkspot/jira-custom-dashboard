<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\RemainingPointsWidget\Integration\RemainingPoints;

use Werkspot\JiraDashboard\BurndownWidget\Domain\GetRemainingPointsBySprintQuery;
use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPoints;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class GetRemainingPointsBySprintQueryTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function getRemainingPointsByLastSprint_whenThereIsASprint_shouldReturnRemainingPointsCollectionOrderedByDate(): void
    {
        $allTeams = $this->teamRepositoryDoctrineAdapter->findAll();
        $teamId = $allTeams[0]->getId();

        $sprint = $this->sprintRepositoryDoctrineAdapter->findActiveByTeam($teamId);

        $getRemainingPointsBySprintQuery = new GetRemainingPointsBySprintQuery($sprint->getId()->id());
        $remainingPointsData = $this->commandBus->handle($getRemainingPointsBySprintQuery);

        $this->assertCount(6, $remainingPointsData);
        /** @var RemainingPoints $remainingPointsDataOne */
        $remainingPointsDataOne = $remainingPointsData[0];
        /** @var RemainingPoints $remainingPointsDataTwo */
        $remainingPointsDataTwo = $remainingPointsData[1];
        $this->assertTrue( $remainingPointsDataOne->getDate() < $remainingPointsDataTwo->getDate());
    }
}
