<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\ConfidenceWidget\Integration\Confidence;

use Werkspot\JiraDashboard\ConfidenceWidget\Domain\SaveConfidenceCommand;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class SaveConfidenceCommandTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function saveNewConfidence_whenDataIsValid_shouldSaveNewConfidenceDataToPersistence(): void
    {
        $allSprints = $this->sprintRepositoryDoctrineAdapter->findAll();
        $sprintId = $allSprints[0]->getId();
        $today = new \DateTimeImmutable('today');

        $saveConfidenceCommand = new SaveConfidenceCommand($sprintId->id(), $today, 5);

        $this->commandBus->handle($saveConfidenceCommand);

        $savedConfidence = $this->confidenceRepositoryDoctrineAdapter->findByDate($today);

        $this->assertEquals($today, $savedConfidence->getDate());
        $this->assertEquals(5, $savedConfidence->getValue()->value());
    }

    /**
     * @test
     */
    public function saveConfidence_whenDateAlreadyExists_shouldUpdateConfidenceData(): void
    {
        $allSprints = $this->sprintRepositoryDoctrineAdapter->findAll();
        $sprintId = $allSprints[0]->getId();
        $today = new \DateTimeImmutable('today');

        $savedConfidenceOneCommand = new SaveConfidenceCommand($sprintId->id(), $today, 5);
        $savedConfidenceTwoCommand = new SaveConfidenceCommand($sprintId->id(), $today, 1);

        $this->commandBus->handle($savedConfidenceOneCommand);
        $this->commandBus->handle($savedConfidenceTwoCommand);

        $savedConfidence = $this->confidenceRepositoryDoctrineAdapter->findByDate($today);

        $this->assertEquals($today, $savedConfidence->getDate());
        $this->assertEquals(1, $savedConfidence->getValue()->value());
    }
}
