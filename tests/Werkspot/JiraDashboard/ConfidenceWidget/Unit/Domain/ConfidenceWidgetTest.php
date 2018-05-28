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
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;
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
        $sprintRepository = $this->getSprintRepository();
        $activeSprint = $sprintRepository->findActive();

        $confidenceRepository = $this->getConfidenceRepository();

        $confidenceWidget = new ConfidenceWidget($sprintRepository, $confidenceRepository);
        $confidenceCollection = $confidenceWidget->getConfidenceBySprint($activeSprint);

        $this->assertCount(9, $confidenceCollection);
        $this->assertInstanceOf(Confidence::class, $confidenceCollection[0]);
        $this->assertTrue(($confidenceCollection[0])->getDate() < ($confidenceCollection[1])->getDate());
    }

    /**
     * @test
     */
    public function saveNewConfidence_whenDataIsValid_shouldSaveNewConfidenceDataToPersistence()
    {
        $sprintRepository = new SprintRepositoryInMemoryAdapter([]);
        $confidenceRepository = new ConfidenceRepositoryInMemoryAdapter([]);

        $today = new DateTimeImmutable('today');

        $confidence = new Confidence($today, ConfidenceValueEnum::five());

        $confidenceWidget = new ConfidenceWidget($sprintRepository, $confidenceRepository);
        $confidenceWidget->saveConfidence($confidence);

        $savedConfidence = $confidenceRepository->findByDate($today);

        $this->assertEquals($confidence, $savedConfidence);
    }

    /**
     * @test
     */
    public function saveConfidence_whenDateAlreadyExists_shouldUpdateConfidenceData()
    {
        $sprintRepository = new SprintRepositoryInMemoryAdapter([]);
        $confidenceRepository = $this->getConfidenceRepository();

        $today = new DateTimeImmutable('today');

        $updatedConfidence = new Confidence($today, ConfidenceValueEnum::five());

        $confidenceWidget = new ConfidenceWidget($sprintRepository, $confidenceRepository);
        $confidenceWidget->saveConfidence($updatedConfidence);

        $savedConfidence = $confidenceRepository->findByDate($today);

        $this->assertEquals($updatedConfidence, $savedConfidence);
    }

    /**
     * @throws \Exception
     */
    private function getSprintRepository(): SprintRepositoryInMemoryAdapter
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
                new DateTimeImmutable('today + 4 days'),
                PositiveNumber::create(1)
            ),
        ]);

        return $sprintRepository;
    }

    /**
     * @throws \Exception
     */
    private function getConfidenceRepository(): ConfidenceRepositoryInMemoryAdapter
    {
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

        return $confidenceRepository;
    }
}
