<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint;

use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
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
     * @throws EntityNotFoundException
     */
    public function findActive(): Sprint;

    public function upsert(Sprint $sprint): void;

    public function getNextSprintNumber(): int;
}
