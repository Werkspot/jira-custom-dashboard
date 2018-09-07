<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\TeamWidget\Integration;

use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\TeamWidget\Domain\GetSprintsQuery;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class GetSprintsQueryTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function getSprintsQuery_whenThereAreSomeSprints_shouldReturnSprintsData()
    {
        /** @var Team[] $allTeamsCollection */
        $allTeamsCollection = $this->teamRepositoryDoctrineAdapter->findAll();
        $team = array_shift($allTeamsCollection);

        $getSprintsQuery = new GetSprintsQuery($team->getId()->id());
        $sprintsData = $this->commandBus->handle($getSprintsQuery);

        $this->assertCount(1, $sprintsData);
        $this->assertInstanceOf(Sprint::class, array_shift($sprintsData));
    }
}
