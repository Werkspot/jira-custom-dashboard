<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SprintWidget\Integration\Sprint;

use Werkspot\JiraDashboard\SprintWidget\Domain\AddNewSprintCommand;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class AddNewSprintCommandTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function addNewSprint_whenDataIsValid_shouldSaveNewSprintToPersistence()
    {
        $allTeams = $this->teamRepositoryDoctrineAdapter->findAll();
        $teamId = $allTeams[0]->getId();
        $startDate = new \DateTimeImmutable('today + 5 days');
        $endDate   = new \DateTimeImmutable('today + 9 days');

        $this->assertCount(1, $this->sprintRepositoryDoctrineAdapter->findAll());

        $addNewSprintCommand = new AddNewSprintCommand($teamId->id(), $startDate, $endDate);
        $this->commandBus->handle($addNewSprintCommand);

        $this->assertCount(2, $this->sprintRepositoryDoctrineAdapter->findAll());
    }
}
