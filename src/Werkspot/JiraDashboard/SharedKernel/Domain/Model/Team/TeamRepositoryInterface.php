<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team;

use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

interface TeamRepositoryInterface
{
    /**
     * @throws EntityNotFoundException
     */
    public function find(Id $id): Team;

    /**
     * @return Team[]|null
     */
    public function findAll(): ?array;
}
