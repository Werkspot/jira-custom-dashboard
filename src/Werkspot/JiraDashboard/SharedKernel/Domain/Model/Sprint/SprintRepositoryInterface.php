<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint;

use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;

interface SprintRepositoryInterface
{
    /**
     * @throws EntityNotFoundException
     */
    public function find(SprintId $id): Sprint;

    /**
     * @return Sprint[]|null
     */
    public function findAll(): ?array;

    /**
     * @throws EntityNotFoundException
     */
    public function findActive(): Sprint;
}
