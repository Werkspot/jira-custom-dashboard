<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SprintWidget\Domain;

use Symfony\Component\HttpFoundation\Response;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Widget\WidgetInterface;

final class SprintWidget implements WidgetInterface
{
    /** @var SprintRepositoryInterface */
    private $sprintRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository)
    {
        $this->sprintRepository = $sprintRepository;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function getActiveSprintByTeam(Team $team): Sprint
    {
        return $this->sprintRepository->findActiveByTeam($team->getId());
    }

    public function addNewSprint(Sprint $sprint): void
    {
        $this->sprintRepository->upsert($sprint);
    }

    public function render(): Response
    {
        return new Response('Sprint Widget');
    }
}
