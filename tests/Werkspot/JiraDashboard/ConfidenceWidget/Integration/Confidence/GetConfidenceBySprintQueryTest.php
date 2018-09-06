<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\ConfidenceWidget\Integration\Confidence;

use Werkspot\JiraDashboard\ConfidenceWidget\Domain\GetConfidenceBySprintQuery;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class GetConfidenceBySprintQueryTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function getConfidenceByLastSprint_whenThereIsASprint_shouldReturnConfidenceCollectionOrderedByDate(): void
    {
        $allSprints = $this->sprintRepositoryDoctrineAdapter->findAll();
        $sprintId = $allSprints[0]->getId();

        $getConfidenceBySprintQuery = new GetConfidenceBySprintQuery($sprintId->id());
        $confidenceData = $this->commandBus->handle($getConfidenceBySprintQuery);

        $this->assertCount(5, $confidenceData);
        $this->assertTrue($confidenceData[0]['date'] < $confidenceData[1]['date']);
    }
}
