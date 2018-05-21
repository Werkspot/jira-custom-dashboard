<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Domain;

use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;

interface ConfidenceRepositoryInterface
{
    /**
     * @throws EntityNotFoundException
     * @return Confidence[]
     */
    public function findBySprint(Sprint $sprint): array;
}
