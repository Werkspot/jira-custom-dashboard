<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\AchievedSprintsWidget\Domain;

use Symfony\Component\HttpFoundation\Response;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Widget\WidgetInterface;

class AchievedSprintsWidget implements WidgetInterface
{
    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository)
    {
        $this->sprintRepository = $sprintRepository;
    }

    public function getAchievedSprints(): ?array
    {
        return $this->sprintRepository->findAchieved();
    }

    public function setSprintAsAchieved(Sprint $sprint): void
    {
        $this->sprintRepository->upsert($sprint);
    }

    public function render(): Response
    {
        return new Response('Achieved Sprints Widget');
    }
}
