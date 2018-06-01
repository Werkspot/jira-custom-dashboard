<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\RemainingPointsWidget\Integration\RemainingPoints;

use Werkspot\JiraDashboard\BurndownWidget\Domain\GetRemainingPointsBySprintQuery;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class GetRemainingPointsBySprintQueryTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function getRemainingPointsByLastSprint_whenThereIsASprint_shouldReturnRemainingPointsCollectionOrderedByDate(): void
    {
        $sprint = $this->sprintRepositoryDoctrineAdapter->findActive();

        $getRemainingPointsBySprintQuery = new GetRemainingPointsBySprintQuery($sprint->getId()->id());
        $remainingPointsData = $this->commandBus->handle($getRemainingPointsBySprintQuery);

        $this->assertCount(10, $remainingPointsData);
        $this->assertTrue($remainingPointsData[0]['date'] < $remainingPointsData[1]['date']);
    }
}
