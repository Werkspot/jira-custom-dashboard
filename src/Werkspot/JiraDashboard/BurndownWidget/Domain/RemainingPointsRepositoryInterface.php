<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BurndownWidget\Domain;

use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;

interface RemainingPointsRepositoryInterface
{
    /**
     * @throws EntityNotFoundException
     * @return RemainingPoints[]
     */
    public function findBySprint(Sprint $sprint): array;

    public function upsert(RemainingPoints $confidence): void;
}
