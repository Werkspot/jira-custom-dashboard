<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\TeamWidget\Application;

use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
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

    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    public function __construct(TeamRepositoryInterface $teamRepository, SprintRepositoryInterface $sprintRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->sprintRepository = $sprintRepository;
    }

    /**
     * @return Team[]
     */
    public function handle(GetTeamsQuery $getTeamsQuery): array
    {
        $teamWidget = new TeamWidget($this->teamRepository, $this->sprintRepository);

        return $teamWidget->getTeams();
    }
}
