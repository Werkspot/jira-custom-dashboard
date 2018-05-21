<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Infrastructure\Persistence\InMemory\Confidence;

use Werkspot\JiraDashboard\ConfidenceWidget\Domain\Confidence;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;

class ConfidenceRepositoryInMemoryAdapter implements ConfidenceRepositoryInterface
{
    /** @var Confidence[] */
    private $inMemoryData = [];

    /**
     * @param Confidence[]|null $data
     */
    public function __construct(array $data = null)
    {
        foreach ($data as $confidence) {
            $this->inMemoryData[$confidence->getDate()->format('Ymd')] = $confidence;
        }

        ksort($this->inMemoryData, SORT_NUMERIC);
    }

    /**
     * @throws EntityNotFoundException
     * @return Confidence[]
     */
    public function findBySprint(Sprint $sprint): array
    {
        $confidenceCollection = [];

        foreach ($this->inMemoryData as $confidence) {
            if ($sprint->getStartDate() <= $confidence->getDate() && $sprint->getEndDate() >= $confidence->getDate()) {
                $confidenceCollection[] = $confidence;
            }
        }

        if (empty($confidenceCollection)) {
            throw new EntityNotFoundException();
        }

        return $confidenceCollection;
    }
}
