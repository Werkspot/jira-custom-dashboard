<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BurndownWidget\Infrastructure\Persistence\InMemory\RemainingPoints;

use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPoints;
use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPointsRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;

class RemainingPointsRepositoryInMemoryAdapter implements RemainingPointsRepositoryInterface
{
    /** @var RemainingPoints[] */
    private $inMemoryData = [];

    /**
     * @param RemainingPoints[]|null $data
     */
    public function __construct(array $data = null)
    {
        foreach ($data as $remainingPoints) {
            $this->inMemoryData[$remainingPoints->getDate()->format('Ymd')] = $remainingPoints;
        }

        ksort($this->inMemoryData, SORT_NUMERIC);
    }

    /**
     * @throws EntityNotFoundException
     * @return RemainingPoints[]
     */
    public function findBySprint(Sprint $sprint): array
    {
        $remainingPointsCollection = array_filter(
            array_values(
                array_map(function(RemainingPoints $remainingPoints) use ($sprint) {
                    if ($remainingPoints->getSprint()->getId() == $sprint->getId()) {
                        return $remainingPoints;
                    }

                    return null;
                }, $this->inMemoryData)
            )
        );

        if (empty($remainingPointsCollection)) {
            throw new EntityNotFoundException();
        }

        return $remainingPointsCollection;
    }

    public function upsert(RemainingPoints $remainingPoints): void
    {
        $remainingPointsKey = $remainingPoints->getDate()->format('Ymd');

        $this->inMemoryData[$remainingPointsKey] = $remainingPoints;
    }
}
