<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Domain;

use DateTimeImmutable;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;

interface ConfidenceRepositoryInterface
{
    /**
     * @throws EntityNotFoundException
     * @return Confidence[]
     */
    public function findBySprint(Sprint $sprint): array;

    /**
     * @throws EntityNotFoundException
     */
    public function findByDate(DateTimeImmutable $date): Confidence;

    public function upsert(Confidence $confidence): void;
}
