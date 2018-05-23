<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\ConfidenceWidget\Integration\Confidence;

use DateTimeImmutable;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\Confidence;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceValueEnum;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\SaveConfidenceCommand;
use Werkspot\Tests\JiraDashboard\SharedKernel\Integration\IntegrationTestAbstract;

class SaveConfidenceTest extends IntegrationTestAbstract
{
    /**
     * @test
     */
    public function saveConfidence_whenDataIsValid_shouldSaveNewConfidenceDataToPersistence(): void
    {
        $today = new DateTimeImmutable('today');

        $savedConfidenceCommand = new SaveConfidenceCommand(
            $today,
            ConfidenceValueEnum::five()
        );

        $this->commandBus->handle($savedConfidenceCommand);

        /** @var Confidence[] */
        $savedConfidence = $this->confidenceRepositoryDoctrineAdapter->findByDate($today);

        $this->assertEquals($today->format('Ymd'), $savedConfidence->getDate()->format('Ymd'));
        $this->assertEquals(5, $savedConfidence->getValue()->value());
    }

    /**
     * @test
     */
    public function saveConfidence_whenDateAlreadyExists_shouldUpdateConfidenceData(): void
    {
        $today = new DateTimeImmutable('today');

        $savedConfidenceOneCommand = new SaveConfidenceCommand(
            $today,
            ConfidenceValueEnum::five()
        );

        $savedConfidenceTwoCommand = new SaveConfidenceCommand(
            $today,
            ConfidenceValueEnum::one()
        );

        $this->commandBus->handle($savedConfidenceOneCommand); // try 1

        $this->commandBus->handle($savedConfidenceTwoCommand); // try 2

        /** @var Confidence[] */
        $savedConfidence = $this->confidenceRepositoryDoctrineAdapter->findByDate($today);

        $this->assertEquals($today->format('Ymd'), $savedConfidence->getDate()->format('Ymd'));
        $this->assertEquals(1, $savedConfidence->getValue()->value());
    }
}
