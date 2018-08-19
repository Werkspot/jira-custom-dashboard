<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\TeamWidget\Integration;

use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\TeamWidget\Domain\GetTeamsQuery;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class GetTeamsQueryTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function getTeamsQuery_whenThereIsATeam_shouldReturnTeamsData()
    {
        $getTeamsQuery = new GetTeamsQuery();
        $teamsData = $this->commandBus->handle($getTeamsQuery);

        $this->assertCount(1, $teamsData);
        $this->assertInstanceOf(Team::class, array_shift($teamsData));
    }
}
