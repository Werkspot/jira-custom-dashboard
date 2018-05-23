<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\ConfidenceWidget\Unit\Domain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\Confidence;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceValueEnum;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceWidget;
use Werkspot\JiraDashboard\ConfidenceWidget\Infrastructure\Persistence\InMemory\Confidence\ConfidenceRepositoryInMemoryAdapter;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Sprint\SprintRepositoryInMemoryAdapter;

class ConfidenceWidgetTest extends TestCase
{
    /**
     * @test
     */
    public function getConfidenceByLastSprint_whenThereIsASprint_shouldReturnConfidenceCollectionOrderedByDate()
    {
        $sprintRepository = new SprintRepositoryInMemoryAdapter([
            new Sprint(
                Id::create(),
                ShortText::create('Sprint title'),
                new Team(
                    Id::create(),
                    ShortText::create('Team name')
                ),
                new DateTimeImmutable('today - 4 days'),
                new DateTimeImmutable('today + 4 days')
            ),
        ]);
        $activeSprint = $sprintRepository->findActive();

        $confidenceRepository = new ConfidenceRepositoryInMemoryAdapter([
            new Confidence(new DateTimeImmutable('today - 4 days'), ConfidenceValueEnum::five()),
            new Confidence(new DateTimeImmutable('today - 3 days'), ConfidenceValueEnum::four()),
            new Confidence(new DateTimeImmutable('today - 2 days'), ConfidenceValueEnum::three()),
            new Confidence(new DateTimeImmutable('today - 1 days'), ConfidenceValueEnum::three()),
            new Confidence(new DateTimeImmutable('today - 0 days'), ConfidenceValueEnum::two()),
            new Confidence(new DateTimeImmutable('today + 1 days'), ConfidenceValueEnum::two()),
            new Confidence(new DateTimeImmutable('today + 2 days'), ConfidenceValueEnum::four()),
            new Confidence(new DateTimeImmutable('today + 3 days'), ConfidenceValueEnum::four()),
            new Confidence(new DateTimeImmutable('today + 4 days'), ConfidenceValueEnum::five()),
        ]);

        $confidenceWidget = new ConfidenceWidget($sprintRepository, $confidenceRepository);
        $confidenceCollection = $confidenceWidget->getConfidenceBySprint($activeSprint);

        $this->assertCount(9, $confidenceCollection);
        $this->assertInstanceOf(Confidence::class, $confidenceCollection[0]);
        $this->assertTrue(($confidenceCollection[0])->getDate() < ($confidenceCollection[1])->getDate());
    }

    /**
     * @test
     * @expectedException \Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException
     */
    public function getConfidenceByLastSprint_whenThereIsNotASprint_shouldThrowAnException()
    {
        $sprintRepository = new SprintRepositoryInMemoryAdapter([]);

        $confidenceRepository = new ConfidenceRepositoryInMemoryAdapter([
            new Confidence(new DateTimeImmutable('today - 4 days'), ConfidenceValueEnum::five()),
            new Confidence(new DateTimeImmutable('today - 3 days'), ConfidenceValueEnum::four()),
            new Confidence(new DateTimeImmutable('today - 2 days'), ConfidenceValueEnum::three()),
            new Confidence(new DateTimeImmutable('today - 1 days'), ConfidenceValueEnum::three()),
            new Confidence(new DateTimeImmutable('today - 0 days'), ConfidenceValueEnum::two()),
            new Confidence(new DateTimeImmutable('today + 1 days'), ConfidenceValueEnum::two()),
            new Confidence(new DateTimeImmutable('today + 2 days'), ConfidenceValueEnum::four()),
            new Confidence(new DateTimeImmutable('today + 3 days'), ConfidenceValueEnum::four()),
            new Confidence(new DateTimeImmutable('today + 4 days'), ConfidenceValueEnum::five()),
        ]);

        $confidenceWidget = new ConfidenceWidget($sprintRepository, $confidenceRepository);
        $confidenceWidget->getConfidenceBySprint($sprintRepository->findActive());
    }

    /**
     * @test
     * @expectedException \Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException
     */
    public function getConfidenceBySprint_whenNotExistingSprint_shouldThrowAnException()
    {
        $existingSprintId = Id::create();
        $notExistingSprintId = Id::create();

        $sprintRepository = new SprintRepositoryInMemoryAdapter([
            new Sprint(
                $existingSprintId,
                ShortText::create('Sprint title'),
                new Team(
                    Id::create(),
                    ShortText::create('Team name')
                ),
                new DateTimeImmutable('today - 4 days'),
                new DateTimeImmutable('today + 4 days')
            ),
        ]);

        $confidenceRepository = new ConfidenceRepositoryInMemoryAdapter([
            new Confidence(new DateTimeImmutable('today - 4 days'), ConfidenceValueEnum::five()),
            new Confidence(new DateTimeImmutable('today - 3 days'), ConfidenceValueEnum::four()),
            new Confidence(new DateTimeImmutable('today - 2 days'), ConfidenceValueEnum::three()),
            new Confidence(new DateTimeImmutable('today - 1 days'), ConfidenceValueEnum::three()),
            new Confidence(new DateTimeImmutable('today - 0 days'), ConfidenceValueEnum::two()),
            new Confidence(new DateTimeImmutable('today + 1 days'), ConfidenceValueEnum::two()),
            new Confidence(new DateTimeImmutable('today + 2 days'), ConfidenceValueEnum::four()),
            new Confidence(new DateTimeImmutable('today + 3 days'), ConfidenceValueEnum::four()),
            new Confidence(new DateTimeImmutable('today + 4 days'), ConfidenceValueEnum::five()),
        ]);

        $confidenceWidget = new ConfidenceWidget($sprintRepository, $confidenceRepository);
        $confidenceWidget->getConfidenceBySprint($sprintRepository->find($notExistingSprintId));
    }

    /**
     * @test
     */
    public function saveConfidence_whenDataIsValid_shouldSaveNewConfidenceDataToPersistence()
    {
        $confidenceRepository = new ConfidenceRepositoryInMemoryAdapter([]);

        $today = new DateTimeImmutable('today');

        $confidence = new Confidence($today, ConfidenceValueEnum::five());

        $confidenceRepository->upsert($confidence);

        $savedConfidence = $confidenceRepository->findByDate($today);

        $this->assertEquals($confidence->getDate(), $savedConfidence->getDate());
        $this->assertEquals($confidence->getValue(), $savedConfidence->getValue());
        $this->assertEquals($confidence->getId(), $savedConfidence->getId());
    }

    /**
     * @test
     * @expectedException \Werkspot\JiraDashboard\SharedKernel\Domain\Exception\InvalidDateException
     */
    public function saveConfidence_whenDataIsNotValid_shouldThrowAnException()
    {
        $confidenceRepository = new ConfidenceRepositoryInMemoryAdapter([]);

        $yesterday = new DateTimeImmutable('yesterday');

        $confidence = new Confidence($yesterday, ConfidenceValueEnum::five());

        $confidenceRepository->upsert($confidence);
    }

    /**
     * @test
     */
    public function saveConfidence_whenDateAlreadyExists_shouldUpdateConfidenceData()
    {
        $confidenceRepository = new ConfidenceRepositoryInMemoryAdapter([]);

        $today = new DateTimeImmutable('today');

        $confidenceOne = new Confidence($today, ConfidenceValueEnum::five());
        $confidenceTwo = new Confidence($today, ConfidenceValueEnum::one());

        // same day, different value
        $confidenceRepository->upsert($confidenceOne);
        $confidenceRepository->upsert($confidenceTwo);

        $savedConfidence = $confidenceRepository->findByDate($today);

        $this->assertEquals($confidenceTwo->getDate(), $savedConfidence->getDate());
        $this->assertEquals($confidenceTwo->getValue(), $savedConfidence->getValue());
        $this->assertEquals($confidenceTwo->getId(), $savedConfidence->getId());
    }
}
