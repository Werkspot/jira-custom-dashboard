<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BugCounterWidget\Infrastructure\Persistence\InMemory\BugCounter;

use Werkspot\JiraDashboard\BugCounterWidget\Domain\BugCounter;
use Werkspot\JiraDashboard\BugCounterWidget\Domain\BugCounterRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;

class BugCounterRepositoryInMemoryAdapter implements BugCounterRepositoryInterface
{
    /** @var BugCounter[] */
    private $inMemoryData = [];

    /**
     * @param BugCounter[]|null $data
     */
    public function __construct(array $data = null)
    {
        foreach ($data as $bugCounter) {
            $this->inMemoryData[$bugCounter->getId()->id()] = $bugCounter;
        }
    }

    public function findBugCounterByTeam(Team $team): ?array
    {
        return array_filter($this->inMemoryData, function(BugCounter $bugCounter) use ($team) {
            if ($bugCounter->getSprint()->getTeam() == $team) {
                return $bugCounter;
            }

            return false;
        });
    }

    public function findBugCounterBySprint(Sprint $sprint): ?BugCounter
    {
        foreach ($this->inMemoryData as $bugCounter) {
            if ($bugCounter->getSprint() == $sprint) {
                return $bugCounter;
            }
        }

        return null;
    }

    public function upsert(BugCounter $bugCounter): void
    {
        $this->inMemoryData[$bugCounter->getId()->id()] = $bugCounter;
    }
}
