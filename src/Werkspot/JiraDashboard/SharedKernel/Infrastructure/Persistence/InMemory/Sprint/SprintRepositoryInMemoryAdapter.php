<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Sprint;

use DateTimeImmutable;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\InvalidDateException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintId;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
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
     * @throws EntityNotFoundException
     */
    public function findActive(): Sprint
    {
        $today = new DateTimeImmutable();

        foreach ($this->inMemoryData as $sprint) {
            if ($sprint->getStartDate() <= $today && $sprint->getEndDate() >= $today) {
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

    public function getNextSprintNumber(): int
    {
        if ($lastPersisted = $this->getLastPersisted()) {
            return $lastPersisted->getNumber()->number() + 1;
        }

        return 0;
    }

    private function sortInMemoryDataByDateAsc(): void
    {
        uasort($this->inMemoryData, function (Sprint $firstSprint, Sprint $secondSprint) {
            return $firstSprint->getStartDate() < $secondSprint->getEndDate();
        });
    }

    private function getLastPersisted(): ?Sprint
    {
        uasort($this->inMemoryData, function (Sprint $firstSprint, Sprint $secondSprint) {
            return $firstSprint->getNumber()->number() < $secondSprint->getNumber()->number();
        });

        reset($this->inMemoryData);

        return current($this->inMemoryData) ? current($this->inMemoryData) : null;
    }
}
