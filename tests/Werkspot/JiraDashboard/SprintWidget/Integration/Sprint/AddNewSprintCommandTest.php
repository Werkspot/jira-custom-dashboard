<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SprintWidget\Integration\Sprint;

use DateTimeImmutable;
use Werkspot\JiraDashboard\SprintWidget\Domain\AddNewSprintCommand;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class AddNewSprintCommandTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function addNewSprint_whenDataIsValid_shouldSaveNewSprintToPersistence()
    {
        $startDate = new DateTimeImmutable('today + 5 days');
        $endDate   = new DateTimeImmutable('today + 9 days');

        $this->assertCount(1, $this->sprintRepositoryDoctrineAdapter->findAll());

        $addNewSprintCommand = new AddNewSprintCommand($startDate, $endDate);
        $this->commandBus->handle($addNewSprintCommand);

        $this->assertCount(2, $this->sprintRepositoryDoctrineAdapter->findAll());
    }
}
