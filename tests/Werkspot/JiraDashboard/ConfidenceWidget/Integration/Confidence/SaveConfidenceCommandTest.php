<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\ConfidenceWidget\Integration\Confidence;

use DateTimeImmutable;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceValueEnum;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\SaveConfidenceCommand;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class SaveConfidenceCommandTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function saveNewConfidence_whenDataIsValid_shouldSaveNewConfidenceDataToPersistence(): void
    {
        $today = new DateTimeImmutable('today');

        $saveConfidenceCommand = new SaveConfidenceCommand($today, ConfidenceValueEnum::five());

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
        $today = new DateTimeImmutable('today');

        $savedConfidenceOneCommand = new SaveConfidenceCommand($today, ConfidenceValueEnum::five());
        $savedConfidenceTwoCommand = new SaveConfidenceCommand($today, ConfidenceValueEnum::one());

        $this->commandBus->handle($savedConfidenceOneCommand); // try 1
        $this->commandBus->handle($savedConfidenceTwoCommand); // try 2

        $savedConfidence = $this->confidenceRepositoryDoctrineAdapter->findByDate($today);

        $this->assertEquals($today, $savedConfidence->getDate());
        $this->assertEquals(1, $savedConfidence->getValue()->value());
    }
}
