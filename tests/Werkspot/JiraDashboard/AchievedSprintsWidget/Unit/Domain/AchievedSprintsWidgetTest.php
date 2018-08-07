<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\AchievedSprintsWidget\Unit\Domain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\AchievedSprintsWidget;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Sprint\SprintRepositoryInMemoryAdapter;

class AchievedSprintsWidgetTest extends TestCase
{
    private const ACHIEVED = true;

    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    public function setUp()
    {
        parent::setUp();

        $this->sprintRepository = new SprintRepositoryInMemoryAdapter([]);
    }

    /**
     * @test
     */
    public function getAchievedSprints_whenAtLeastOneSprintHasBeenAchieved_shouldReturnAListOfAchievedSprints()
    {
        $startDate = new DateTimeImmutable('today - 4 days');
        $endDate   = new DateTimeImmutable('today + 4 days');

        $this->populateSprintRepository($startDate, $endDate);

        $sprint = $this->sprintRepository->findActive();
        $sprint->setAchieved(self::ACHIEVED);

        $achievedSprintWidget = new AchievedSprintsWidget($this->sprintRepository);
        $achievedSprints = $achievedSprintWidget->getAchievedSprints();

        $this->assertEquals(1, $achievedSprints->getAchieved()->number());
        $this->assertEquals(true, $sprint->isAchieved());
    }

    /**
     * @test
     * @expectedException \Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException
     */
    public function getAchievedSprints_whenNoneOfTheSprintsHasBeenAchieved_shouldThrowAnException()
    {
        $startDate = new DateTimeImmutable('today - 4 days');
        $endDate   = new DateTimeImmutable('today + 4 days');

        $this->populateSprintRepository($startDate, $endDate);

        $achievedSprintWidget = new AchievedSprintsWidget($this->sprintRepository);
        $achievedSprintWidget->getAchievedSprints();
    }

    private function populateSprintRepository(DateTimeImmutable $startDate, DateTimeImmutable $endDate): void
    {
        $this->sprintRepository = new SprintRepositoryInMemoryAdapter([
            new Sprint(
                Id::create(),
                ShortText::create('Sprint title 1'),
                new Team(
                    Id::create(),
                    ShortText::create('Team name')
                ),
                $startDate,
                $endDate,
                PositiveNumber::create(0)
            ),
        ]);
    }
}
