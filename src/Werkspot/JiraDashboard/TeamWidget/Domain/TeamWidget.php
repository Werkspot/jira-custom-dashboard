<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\TeamWidget\Domain;

use Symfony\Component\HttpFoundation\Response;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\TeamRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Widget\WidgetInterface;

class TeamWidget implements WidgetInterface
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
     * @return Team[]|null
     */
    public function getTeams(): ?array
    {
        return $this->teamRepository->findAll();
    }

    public function addTeam(Team $team): void
    {
        $this->teamRepository->upsert($team);
    }

    public function render(): Response
    {
        return new Response('Achieved Sprints Widget');
    }

    /**
     * @return Sprint[]|null
     */
    public function getSprints(Team $team): ?array
    {
        return $this->sprintRepository->findAllByTeam($team->getId());
    }
}
