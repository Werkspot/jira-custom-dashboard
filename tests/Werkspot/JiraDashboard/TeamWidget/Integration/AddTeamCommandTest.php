<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\TeamWidget\Integration;

use Werkspot\JiraDashboard\TeamWidget\Domain\AddTeamCommand;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class AddTeamCommandTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function addTeam_whenDataIsValid_shouldSaveNewTeamToPersistence()
    {
        $teamName = 'New Team Name';

        $addTeamCommand = new AddTeamCommand($teamName);
        $this->commandBus->handle($addTeamCommand);

        $this->assertCount(2, $this->teamRepositoryDoctrineAdapter->findAll());
    }
}
