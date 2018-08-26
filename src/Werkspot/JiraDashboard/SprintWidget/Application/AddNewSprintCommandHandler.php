<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SprintWidget\Application;

use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\TeamRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;
use Werkspot\JiraDashboard\SprintWidget\Domain\AddNewSprintCommand;
use Werkspot\JiraDashboard\SprintWidget\Domain\SprintWidget;

class AddNewSprintCommandHandler
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

    public function handle(AddNewSprintCommand $command): void
    {
        $team = $this->teamRepository->find(Id::create($command->teamId()));

        $nextSprintNumber = $this->sprintRepository->getNextSprintNumberByTeam($team);
        $title = ShortText::create('Sprint ' . $nextSprintNumber);

        $sprint = new Sprint(
            Id::create(),
            $title,
            $team,
            $command->startDate(),
            $command->endDate(),
            PositiveNumber::create($nextSprintNumber)
        );

        $sprintWidget = new SprintWidget($this->sprintRepository);
        $sprintWidget->addNewSprint($sprint);
    }
}
