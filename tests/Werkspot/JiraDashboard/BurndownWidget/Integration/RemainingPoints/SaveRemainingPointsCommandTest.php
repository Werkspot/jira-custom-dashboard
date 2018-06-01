<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\BurndownWidget\Integration\RemainingPoints;

use DateTimeImmutable;
use Werkspot\JiraDashboard\BurndownWidget\Domain\SaveRemainingPointsCommand;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class SaveRemainingPointsCommandTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function saveNewRemainingPoints_whenDataIsValid_shouldSaveNewRemainingPointsDataToPersistence(): void
    {
        $sprint = $this->sprintRepositoryDoctrineAdapter->findActive();

        $today = new DateTimeImmutable('today');

        $saveRemainingPointsCommand = new SaveRemainingPointsCommand($sprint->getId()->id(), $today, 5);

        $this->commandBus->handle($saveRemainingPointsCommand);

        $savedRemainingPoints = $this->remainingPointsRepositoryDoctrineAdapter->findByDate($today);

        $this->assertEquals($today, $savedRemainingPoints->getDate());
        $this->assertEquals(5, $savedRemainingPoints->getValue()->number());
    }

    /**
     * @test
     */
    public function saveRemainingPoints_whenDateAlreadyExists_shouldUpdateRemainingPointsData(): void
    {
        $sprint = $this->sprintRepositoryDoctrineAdapter->findActive();

        $today = new DateTimeImmutable('today');

        $savedRemainingPointsOneCommand = new SaveRemainingPointsCommand($sprint->getId()->id(), $today, 5);
        $savedRemainingPointsTwoCommand = new SaveRemainingPointsCommand($sprint->getId()->id(), $today, 1);

        $this->commandBus->handle($savedRemainingPointsOneCommand);
        $this->commandBus->handle($savedRemainingPointsTwoCommand);

        $savedRemainingPoints = $this->remainingPointsRepositoryDoctrineAdapter->findByDate($today);

        $this->assertEquals($today, $savedRemainingPoints->getDate());
        $this->assertEquals(1, $savedRemainingPoints->getValue()->number());
    }
}
