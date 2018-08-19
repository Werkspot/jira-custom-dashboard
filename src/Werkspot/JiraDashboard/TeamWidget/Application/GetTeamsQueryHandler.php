<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\TeamWidget\Application;

use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\TeamRepositoryInterface;
use Werkspot\JiraDashboard\TeamWidget\Domain\GetTeamsQuery;
use Werkspot\JiraDashboard\TeamWidget\Domain\TeamWidget;

class GetTeamsQueryHandler
{
    /**
     * @var TeamRepositoryInterface
     */
    private $teamRepository;

    public function __construct(TeamRepositoryInterface $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * @return Team[]
     */
    public function handle(GetTeamsQuery $getTeamsQuery): array
    {
        $teamWidget = new TeamWidget($this->teamRepository);

        return $teamWidget->getTeams();
    }
}
