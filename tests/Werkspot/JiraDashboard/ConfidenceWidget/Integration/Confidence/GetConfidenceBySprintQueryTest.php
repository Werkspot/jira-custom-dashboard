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

        $getConfidenceBySprintQuery = new GetConfidenceBySprintQuery();
        $confidenceData = $this->commandBus->handle($getConfidenceBySprintQuery);

        $this->assertCount(6, $confidenceData);
        $this->assertTrue($confidenceData[0]['date'] < $confidenceData[1]['date']);
    }
}
