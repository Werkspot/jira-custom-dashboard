<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BurndownWidget\Domain;

use DateTimeImmutable;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;

interface RemainingPointsRepositoryInterface
{
    /**
     * @return RemainingPoints[]
     */
    public function findBySprint(Sprint $sprint): ?array;

    public function findByDate(DateTimeImmutable $date): ?RemainingPoints;

    public function upsert(RemainingPoints $confidence): void;
}
