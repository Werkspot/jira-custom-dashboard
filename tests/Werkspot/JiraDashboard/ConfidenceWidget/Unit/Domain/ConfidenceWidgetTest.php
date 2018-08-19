<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\ConfidenceWidget\Unit\Domain;

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
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Team\TeamRepositoryInMemoryAdapter;

class ConfidenceWidgetTest extends TestCase
{
    /**
     * @test
     */
    public function getConfidenceByLastSprint_whenThereIsASprint_shouldReturnConfidenceCollectionOrderedByDate()
    {
        $teamRepository = $this->getTeamRepository();
        $teams = $teamRepository->findAll();
        $team = array_shift($teams);

        $sprintRepository = $this->getSprintRepository($team);
        $activeSprint = $sprintRepository->findActiveByTeam($team->getId());

        $confidenceRepository = $this->getConfidenceRepository($activeSprint);

        $confidenceWidget = new ConfidenceWidget($sprintRepository, $confidenceRepository);
        $confidenceCollection = $confidenceWidget->getConfidenceBySprint($activeSprint);

        $this->assertCount(5, $confidenceCollection);
        $this->assertInstanceOf(Confidence::class, $confidenceCollection[0]);
        $this->assertTrue(($confidenceCollection[0])->getDate() < ($confidenceCollection[1])->getDate());
    }

    /**
     * @test
     */
    public function saveNewConfidence_whenDataIsValid_shouldSaveNewConfidenceDataToPersistence()
    {
        $teamRepository = $this->getTeamRepository();
        $teams = $teamRepository->findAll();
        $team = array_shift($teams);

        $sprintRepository = $this->getSprintRepository($team);
        $activeSprint = $sprintRepository->findActiveByTeam($team->getId());

        $confidenceRepository = new ConfidenceRepositoryInMemoryAdapter([]);

        $today = new \DateTimeImmutable('today');

        $confidence = new Confidence($today, ConfidenceValueEnum::five(), $activeSprint);

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
        $teamRepository = $this->getTeamRepository();
        $teams = $teamRepository->findAll();
        $team = array_shift($teams);

        $sprintRepository = $this->getSprintRepository($team);
        $activeSprint = $sprintRepository->findActiveByTeam($team->getId());

        $confidenceRepository = $this->getConfidenceRepository($activeSprint);

        $today = new \DateTimeImmutable('today');

        $updatedConfidence = new Confidence($today, ConfidenceValueEnum::five(), $activeSprint);

        $confidenceWidget = new ConfidenceWidget($sprintRepository, $confidenceRepository);
        $confidenceWidget->saveConfidence($updatedConfidence);

        $savedConfidence = $confidenceRepository->findByDate($today);

        $this->assertEquals($updatedConfidence, $savedConfidence);
    }

    private function getTeamRepository(): TeamRepositoryInMemoryAdapter
    {
        $teamRepository = new TeamRepositoryInMemoryAdapter([
            new Team(
                Id::create(),
                ShortText::create('Team 1')
            ),
        ]);

        return $teamRepository;
    }

    private function getSprintRepository(Team $team): SprintRepositoryInMemoryAdapter
    {
        $sprintRepository = new SprintRepositoryInMemoryAdapter([
            new Sprint(
                Id::create(),
                ShortText::create('Sprint title'),
                $team,
                new \DateTimeImmutable('today - 4 days'),
                new \DateTimeImmutable('today + 4 days'),
                PositiveNumber::create(1)
            ),
        ]);

        return $sprintRepository;
    }

    /**
     * @throws \Exception
     */
    private function getConfidenceRepository(Sprint $sprint): ConfidenceRepositoryInMemoryAdapter
    {
        $confidenceRepository = new ConfidenceRepositoryInMemoryAdapter([
            new Confidence(new \DateTimeImmutable('today - 4 days'), ConfidenceValueEnum::five(), $sprint),
            new Confidence(new \DateTimeImmutable('today - 3 days'), ConfidenceValueEnum::four(), $sprint),
            new Confidence(new \DateTimeImmutable('today - 2 days'), ConfidenceValueEnum::three(), $sprint),
            new Confidence(new \DateTimeImmutable('today - 1 days'), ConfidenceValueEnum::three(), $sprint),
            new Confidence(new \DateTimeImmutable('today - 0 days'), ConfidenceValueEnum::two(), $sprint),
        ]);

        return $confidenceRepository;
    }
}
