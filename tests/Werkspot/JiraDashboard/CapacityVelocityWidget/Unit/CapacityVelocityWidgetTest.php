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

    /**
     * @var Id
     */
    private static $teamId;

    /**
     * @var Id
     */
    private static $teamIdWithoutCapacity;

    /**
     * @var Id
     */
    private static $teamIdWithoutVelocity;

    public function setUp()
    {
        parent::setUp();

        $this->sprintRepository = new SprintRepositoryInMemoryAdapter([]);
        self::$teamId = Id::create();
        self::$teamIdWithoutCapacity = Id::create();
        self::$teamIdWithoutVelocity = Id::create();
    }

    private static function getSprintDataSource()
    {
        return [
            [
                self::$teamId,
                1.2,
                3,
            ],
            [
                self::$teamId,
                5.5,
                2,
            ],
            [
                Id::create(),
                7,
                4,
            ],
            [
                Id::create(),
                5,
                3,
            ],
            [
                self::$teamIdWithoutCapacity,
                null,
                3,
            ],
            [
                self::$teamIdWithoutVelocity,
                3,
                null,
            ],
        ];
    }

    /**
     * @test
     */
    public function getCapacityOrderedBySprintNumber_whenSprintCapacityIsNotSet_shouldReturnZeroAsCapacity()
    {
        $this->populateSprintRepository();

        $capacityVelocityWidget = new CapacityVelocityWidget($this->sprintRepository);

        $capacityCollection = $capacityVelocityWidget->getCapacityOrderedBySprintNumber(self::$teamIdWithoutCapacity);

        $this->assertEquals(0, $capacityCollection[0][5]);
    }

    /**
     * @test
     */
    public function getCapacityOrderedBySprintNumber_whenSprintCapacityIsSet_shouldReturnGivenCapacityValue()
    {
        $this->populateSprintRepository();

        $capacityVelocityWidget = new CapacityVelocityWidget($this->sprintRepository);

        $capacityCollection = $capacityVelocityWidget->getCapacityOrderedBySprintNumber(self::$teamId);

        $this->assertEquals(5.5, $capacityCollection[0][2]);
        $this->assertEquals(1.2, $capacityCollection[1][1]);
    }

    /**
     * @test
     */
    public function getVelocityOrderedBySprintNumber_whenSprintVelocityIsNotSet_shouldReturnZeroAsVelocity()
    {
        $this->populateSprintRepository();

        $capacityVelocityWidget = new CapacityVelocityWidget($this->sprintRepository);

        $velocityCollection = $capacityVelocityWidget->getVelocityOrderedBySprintNumber(self::$teamIdWithoutVelocity);

        $this->assertEquals(0, $velocityCollection[0][6]);
    }

    /**
     * @test
     */
    public function getVelocityOrderedBySprintNumber_whenSprintVelocityIsSet_shouldReturnGivenVelocityValue()
    {
        $this->populateSprintRepository();

        $capacityVelocityWidget = new CapacityVelocityWidget($this->sprintRepository);

        $velocityCollection = $capacityVelocityWidget->getVelocityOrderedBySprintNumber(self::$teamId);

        $this->assertEquals(2, $velocityCollection[0][2]);
        $this->assertEquals(3, $velocityCollection[1][1]);
    }

    private function populateSprintRepository(): void
    {
        $startDate = new \DateTimeImmutable('today - 4 days');
        $endDate   = new \DateTimeImmutable('today + 4 days');

        $sprintArray = [];

        foreach (self::getSprintDataSource() as $number => $sprintData) {
            $sprint = new Sprint(
                Id::create(),
                ShortText::create('Sprint Title'),
                new Team(
                    $sprintData[0],
                    ShortText::create('Team Name')
                ),
                $startDate,
                $endDate,
                PositiveNumber::create($number + 1)
            );

            if (null !== $sprintData[1]) {
                $sprint->setCapacity($sprintData[1]);
            }

            if (null !== $sprintData[2]) {
                $sprint->setVelocity($sprintData[2]);
            }

            $sprintArray[] = $sprint;
        }

        $this->sprintRepository = new SprintRepositoryInMemoryAdapter($sprintArray);
    }
}
