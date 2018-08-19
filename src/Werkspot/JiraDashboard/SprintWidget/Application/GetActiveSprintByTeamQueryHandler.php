<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SprintWidget\Application;

use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\TeamRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SprintWidget\Domain\GetActiveSprintByTeamQuery;
use Werkspot\JiraDashboard\SprintWidget\Domain\SprintWidget;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;

class GetActiveSprintByTeamQueryHandler
{
    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    /**
     * @var TeamRepositoryInterface
     */
    private $teamRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository, TeamRepositoryInterface $teamRepository)
    {
        $this->sprintRepository = $sprintRepository;
        $this->teamRepository = $teamRepository;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function handle(GetActiveSprintByTeamQuery $activeSprintByTeamQuery): Sprint
    {
        $sprintWidget = new SprintWidget($this->sprintRepository);

        $team = $this->teamRepository->find(Id::create($activeSprintByTeamQuery->teamId()));

        return $sprintWidget->getActiveSprintByTeam($team );
    }
}
