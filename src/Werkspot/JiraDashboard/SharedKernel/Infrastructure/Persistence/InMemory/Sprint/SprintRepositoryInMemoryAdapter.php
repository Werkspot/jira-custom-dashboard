<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Sprint;

use DateTimeImmutable;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
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
}
