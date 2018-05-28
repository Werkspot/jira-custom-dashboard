<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SprintWidget\Integration\Sprint;

use DateTimeImmutable;
use Werkspot\JiraDashboard\SprintWidget\Domain\GetActiveSprintQuery;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class GetActiveSprintQueryTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function getActiveSprintQuery_whenThereIsAnActiveSprint_shouldReturnActiveSprintData()
    {
        $today = new DatetimeImmutable('today');

        $getActiveSprintQuery = new GetActiveSprintQuery();
        $activeSprint = $this->commandBus->handle($getActiveSprintQuery);

        $this->assertTrue($today >= $activeSprint->getStartDate());
        $this->assertTrue($today <= $activeSprint->getEndDate());
        $this->assertEquals('Sprint 0', $activeSprint->getTitle());
    }
}
