<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\TeamWidget\Domain;

use Symfony\Component\HttpFoundation\Response;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\TeamRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Widget\WidgetInterface;

class TeamWidget implements WidgetInterface
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
}
