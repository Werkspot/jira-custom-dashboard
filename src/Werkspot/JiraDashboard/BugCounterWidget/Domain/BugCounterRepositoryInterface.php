<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BugCounterWidget\Domain;

use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;

interface BugCounterRepositoryInterface
{
    /**
     * @return BugCounter[]|null
     */
    public function findBugCounterByTeam(Team $team): ?array;

    public function findBugCounterBySprint(Sprint $sprint): ?BugCounter;

    public function upsert(BugCounter $bugCounter): void;
}
