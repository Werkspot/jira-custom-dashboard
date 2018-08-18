<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\CapacityVelocityWidget\Unit;

use PHPUnit\Framework\TestCase;
use Werkspot\JiraDashboard\CapacityVelocityWidget\Domain\CapacityVelocityWidget;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Sprint\SprintRepositoryInMemoryAdapter;

class CapacityVelocityWidgetTest extends TestCase
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
    public function getCapacityOrderedBySprintNumber_whenSprintCapacitySprintIsNotSet_shouldReturnZeroAsCapacity()
    {
        $startDate = new \DateTimeImmutable('today - 4 days');
        $endDate   = new \DateTimeImmutable('today + 4 days');

        $this->populateSprintRepository($startDate, $endDate, null);

        $capacityVelocityWidget = new CapacityVelocityWidget($this->sprintRepository);

        $capacityCollection = $capacityVelocityWidget->getCapacityOrderedBySprintNumber();

        $this->assertEquals(0, $capacityCollection[0][0]);
    }

    /**
     * @test
     */
    public function getCapacityOrderedBySprintNumber_whenSprintCapacitySprintIsSet_shouldReturnGivenCapacityValue()
    {
        $startDate = new \DateTimeImmutable('today - 4 days');
        $endDate   = new \DateTimeImmutable('today + 4 days');

        $this->populateSprintRepository($startDate, $endDate, 1.2);

        $capacityVelocityWidget = new CapacityVelocityWidget($this->sprintRepository);

        $capacityCollection = $capacityVelocityWidget->getCapacityOrderedBySprintNumber();

        $this->assertEquals(1.2, $capacityCollection[0][0]);
    }

    private function populateSprintRepository(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate, float $capacity = null): void
    {
        $sprint = new Sprint(
            Id::create(),
            ShortText::create('Sprint title 1'),
            new Team(
                Id::create(),
                ShortText::create('Team name')
            ),
            $startDate,
            $endDate,
            PositiveNumber::create(0)
        );

        if (null !== $capacity) {
            $sprint->setCapacity($capacity);
        }

        $this->sprintRepository = new SprintRepositoryInMemoryAdapter([$sprint]);
    }
}
