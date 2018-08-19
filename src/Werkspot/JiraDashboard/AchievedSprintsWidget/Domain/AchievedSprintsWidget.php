<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\AchievedSprintsWidget\Domain;

use Symfony\Component\HttpFoundation\Response;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Widget\WidgetInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;

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

    public function getAchievedSprints(Id $teamId): AchievedSprints
    {
        return new AchievedSprints(
            PositiveNumber::create(count($this->sprintRepository->findAchievedByTeam($teamId))), // @todo refactor to repository method
            PositiveNumber::create(count($this->sprintRepository->findAllByTeam($teamId))) // @todo refactor to repository method
        );
    }

    public function setSprintAsAchieved(?Sprint $sprint, bool $isAchieved): void
    {
        if (!is_null($sprint)) {
            $sprint->setAchieved($isAchieved);
        }

        $this->sprintRepository->upsert($sprint);
    }

    public function render(): Response
    {
        return new Response('Achieved Sprints Widget');
    }
}
