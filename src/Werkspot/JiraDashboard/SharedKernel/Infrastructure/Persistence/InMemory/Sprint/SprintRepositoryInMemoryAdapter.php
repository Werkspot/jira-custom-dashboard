<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Sprint;

use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\InvalidDateException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

class SprintRepositoryInMemoryAdapter implements SprintRepositoryInterface
{
    /** @var Sprint[] */
    private $inMemoryData = [];

    /**
     * @param Sprint[] $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $sprint) {
            $this->inMemoryData[$sprint->getId()->id()] = $sprint;
        }

        $this->sortInMemoryDataByDateAsc();
    }

    /**
     * @throws EntityNotFoundException
     */
    public function find(Id $id): Sprint
    {
        $sprintIdString = $id->id();

        if (!array_key_exists($sprintIdString, $this->inMemoryData)) {
            throw new EntityNotFoundException();
        }

        return $this->inMemoryData[$sprintIdString];
    }

    /**
     * @return Sprint[]|null
     */
    public function findAll(): ?array
    {
        return $this->inMemoryData;
    }

    /**
     * @return Sprint[]|null
     */
    public function findAllByTeam(Id $teamId): ?array
    {
        $sprintArray = array_filter($this->inMemoryData, function (Sprint $sprint) use ($teamId) {
            if ($sprint->getTeam()->getId() == $teamId) {
                return $sprint;
            }

            return false;
        });

        return $sprintArray;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findActiveByTeam(Id $teamId): Sprint
    {
        $today = new \DateTimeImmutable((new \DateTimeImmutable())->format('Y-m-d'));

        foreach ($this->inMemoryData as $sprint) {
            if ($sprint->getTeam()->getId() == $teamId
                && $sprint->getStartDate() <= $today
                && $sprint->getEndDate() >= $today
            ) {
                return $sprint;
            }
        }

        throw new EntityNotFoundException();
    }

    /**
     * @throws InvalidDateException
     */
    public function upsert(Sprint $sprint): void
    {
        $this->inMemoryData[$sprint->getId()->id()] = $sprint;

        $this->sortInMemoryDataByDateAsc();
    }

    public function getNextSprintNumberByTeam(Team $team): int
    {
        if ($lastPersisted = $this->getLastPersisted($team)) {
            return $lastPersisted->getNumber()->number() + 1;
        }

        return 0;
    }

    /**
     * @return Sprint[]|null
     * @throws EntityNotFoundException
     */
    public function findAchievedByTeam(Id $teamId): ?array
    {
        $achieved = array_filter($this->inMemoryData, function (Sprint $sprint) use ($teamId) {
            if ($sprint->getTeam()->getId() == $teamId && $sprint->isAchieved()) {
                return $sprint;
            }

            return false;
        });

        if (empty($achieved)) {
            throw new EntityNotFoundException();
        }

        return $achieved;
    }

    public function findAllOrderByNumber(): ?array
    {
        $this->sortInMemoryDataByNumberAsc();

        return $this->inMemoryData;
    }

    public function findAllByTeamOrderByNumber(Id $teamId): ?array
    {
        $this->sortInMemoryDataByNumberAsc();

        return $this->findAllByTeam($teamId);
    }

    private function sortInMemoryDataByDateAsc(): void
    {
        uasort($this->inMemoryData, function (Sprint $firstSprint, Sprint $secondSprint) {
            return $firstSprint->getStartDate() < $secondSprint->getEndDate();
        });
    }

    private function getLastPersisted(Team $team): ?Sprint
    {
        uasort($this->inMemoryData, function (Sprint $firstSprint, Sprint $secondSprint) use ($team) {
            if ($firstSprint->getTeam()->getId() == $team->getId()) {
                return $firstSprint->getNumber()->number() < $secondSprint->getNumber()->number();
            } else {
                return false;
            }
        });

        reset($this->inMemoryData);

        return current($this->inMemoryData) ? current($this->inMemoryData) : null;
    }

    private function sortInMemoryDataByNumberAsc()
    {
        uasort($this->inMemoryData, function (Sprint $firstSprint, Sprint $secondSprint) {
            return $firstSprint->getNumber() < $secondSprint->getNumber();
        });
    }
}
