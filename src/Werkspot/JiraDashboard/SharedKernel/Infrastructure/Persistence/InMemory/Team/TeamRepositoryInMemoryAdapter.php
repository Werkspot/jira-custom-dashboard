<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Persistence\InMemory\Team;

use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\TeamRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

class TeamRepositoryInMemoryAdapter implements TeamRepositoryInterface
{
    /** @var Team[] */
    private $inMemoryData = [];

    /**
     * @param Team[] $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $team) {
            $this->inMemoryData[$team->getId()->id()] = $team;
        }

        $this->sortInMemoryDataByDateAsc();
    }

    /**
     * @throws EntityNotFoundException
     */
    public function find(Id $id): Team
    {
        $teamIdString = $id->id();

        if (!array_key_exists($teamIdString, $this->inMemoryData)) {
            throw new EntityNotFoundException();
        }

        return $this->inMemoryData[$teamIdString];
    }

    /**
     * @return Team[]|null
     */
    public function findAll(): ?array
    {
        return $this->inMemoryData;
    }

    private function sortInMemoryDataByDateAsc(): void
    {
        uasort($this->inMemoryData, function (Sprint $firstSprint, Sprint $secondSprint) {
            return $firstSprint->getStartDate() < $secondSprint->getEndDate();
        });
    }
}
