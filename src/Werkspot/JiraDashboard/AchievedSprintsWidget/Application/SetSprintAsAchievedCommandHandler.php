<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\AchievedSprintsWidget\Application;

use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\AchievedSprintsWidget;
use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\SetSprintAsAchievedCommand;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;

class SetSprintAsAchievedCommandHandler
{
    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository)
    {
        $this->sprintRepository = $sprintRepository;
    }

    public function handle(SetSprintAsAchievedCommand $achievedCommand): void
    {
        $achievedSprintsWidget = new AchievedSprintsWidget($this->sprintRepository);

        $sprint = $this->sprintRepository->find($achievedCommand->sprintId());

        if (!is_null($sprint)) {
            $sprint->setAchieved($achievedCommand->isAchieved());
        }

        $achievedSprintsWidget->setSprintAsAchieved($sprint);
    }
}