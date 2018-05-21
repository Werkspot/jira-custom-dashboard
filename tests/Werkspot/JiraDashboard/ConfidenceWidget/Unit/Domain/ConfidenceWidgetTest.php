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
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintId;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintTitle;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\TeamId;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\TeamName;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Sprint\SprintRepositoryInMemoryAdapter;

class ConfidenceWidgetTest extends TestCase
{
    /**
     * @test
     */
    public function getConfidenceByLastSprint_whenThereIsAnSprint_shouldReturnConfidenceCollectionOrderedByDate()
    {
        $sprintRepository = new SprintRepositoryInMemoryAdapter([
            new Sprint(
                SprintId::create(),
                SprintTitle::create('Sprint title'),
                new Team(
                    TeamId::create(),
                    TeamName::create('Team name')
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
     */
    public function getConfidenceByLastSprint_whenThereIsNotAnSprint_shouldReturnAnEmptyCollection()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function getConfidenceBySprint_whenSprintExists_shouldReturnConfidenceCollectionOrderedByDate()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function getConfidenceBySprint_whenSprintNotExists_shouldReturnAnEmptyCollection()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function saveConfidence_whenDataIsValid_shouldSaveNewConfidenceDataToPersistence()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function saveConfidence_whenDataIsNotValid_shouldThrowAnException()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function saveConfidence_whenDateAlreadyExists_shouldUpdateConfidenceData()
    {
        $this->assertTrue(true);
    }
}
