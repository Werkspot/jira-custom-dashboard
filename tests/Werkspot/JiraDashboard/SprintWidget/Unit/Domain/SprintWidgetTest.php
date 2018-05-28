<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SprintWidget\Unit\Domain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Sprint\SprintRepositoryInMemoryAdapter;
use Werkspot\JiraDashboard\SprintWidget\Domain\SprintWidget;

class SprintWidgetTest extends TestCase
{
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
    public function getActiveSprint_whenThereIsAnActiveSprint_shouldReturnActiveSprintData()
    {
        $startDate = new DateTimeImmutable('today - 4 days');
        $endDate   = new DateTimeImmutable('today + 4 days');

        $this->populateSprintRepository($startDate, $endDate);

        $sprintWidget = new SprintWidget($this->sprintRepository);
        $sprint       = $sprintWidget->getActiveSprint();

        $this->assertCount(1, $this->sprintRepository->findAll());
        $this->assertEquals($startDate, $sprint->getStartDate());
        $this->assertEquals($endDate, $sprint->getEndDate());
    }

    /**
     * @test
     * @expectedException \Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException
     */
    public function getActiveSprint_whenThereIsNotAnActiveSprint_shouldThrowAnException()
    {
        $sprintWidget = new SprintWidget($this->sprintRepository);
        $sprintWidget->getActiveSprint();
    }

    /**
     * @test
     */
    public function addNewSprint_whenThereIsNoSprints_shouldSaveNewSprintToPersistence()
    {
        $nextSprintStartDate = new DateTimeImmutable('today + 5 days');
        $nextSprintEndDate   = new DateTimeImmutable('today + 9 days');

        $newSprintNumber = $this->sprintRepository->getNextSprintNumber();

        $sprint = new Sprint(
            Id::create(),
            ShortText::create("Sprint $newSprintNumber"),
            new Team(
                Id::create(),
                ShortText::create('Team name')
            ),
            $nextSprintStartDate,
            $nextSprintEndDate,
            PositiveNumber::create($newSprintNumber)
        );

        $sprintWidget = new SprintWidget($this->sprintRepository);
        $sprintWidget->addNewSprint($sprint);

        $newSprintAdded = $this->sprintRepository->find($sprint->getId());

        $this->assertEquals($newSprintNumber, $newSprintAdded->getNumber()->number());
        $this->assertEquals(0, $newSprintAdded->getNumber()->number());
    }

    /**
     * @test
     */
    public function addNewSprint_whenThereIsAnSprint_shouldSaveNewSprintToPersistence()
    {
        $currentSprintStartDate = new DateTimeImmutable('today - 4 days');
        $currentSprintEndDate   = new DateTimeImmutable('today + 4 days');

        $this->populateSprintRepository($currentSprintStartDate, $currentSprintEndDate);

        $nextSprintStartDate = new DateTimeImmutable('today + 4 days');
        $nextSprintEndDate   = new DateTimeImmutable('today + 9 days');

        $newSprintNumber = $this->sprintRepository->getNextSprintNumber();

        $sprint = new Sprint(
            Id::create(),
            ShortText::create("Sprint $newSprintNumber"),
            new Team(
                Id::create(),
                ShortText::create('Team name')
            ),
            $nextSprintStartDate,
            $nextSprintEndDate,
            PositiveNumber::create($newSprintNumber)
        );

        $sprintWidget = new SprintWidget($this->sprintRepository);
        $sprintWidget->addNewSprint($sprint);

        $newSprintAdded = $this->sprintRepository->find($sprint->getId());

        $this->assertEquals($newSprintNumber, $newSprintAdded->getNumber()->number());
        $this->assertEquals(1, $newSprintAdded->getNumber()->number());
    }

    private function populateSprintRepository(DateTimeImmutable $startDate = null, DateTimeImmutable $endDate): void
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
