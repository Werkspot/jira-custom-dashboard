<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint;

use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

interface SprintRepositoryInterface
{
    /**
     * @throws EntityNotFoundException
     */
    public function find(Id $id): Sprint;

    /**
     * @return Sprint[]|null
     */
    public function findAll(): ?array;

    /**
     * @return Sprint[]|null
     */
    public function findAllByTeam(Id $teamId): ?array;

    /**
     * @throws EntityNotFoundException
     */
    public function findActiveByTeam(Id $teamId): Sprint;

    public function upsert(Sprint $sprint): void;

    public function getNextSprintNumberByTeam(Team $team): int;

    /**
     * @return Sprint[]|null
     * @throws EntityNotFoundException
     */
    public function findAchievedByTeam(Id $teamId): ?array;
}
