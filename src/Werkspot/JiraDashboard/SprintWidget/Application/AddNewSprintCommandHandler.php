<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SprintWidget\Application;

use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;
use Werkspot\JiraDashboard\SprintWidget\Domain\AddNewSprintCommand;
use Werkspot\JiraDashboard\SprintWidget\Domain\SprintWidget;

class AddNewSprintCommandHandler
{
    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository)
    {
        $this->sprintRepository = $sprintRepository;
    }

    public function handle(AddNewSprintCommand $command): void
    {
        $nextSprintNumber = $this->sprintRepository->getNextSprintNumber();

        $title = ShortText::create('Sprint ' . $nextSprintNumber);
        $teamName = ShortText::create('Hardcoded team');

        $sprint = new Sprint(
            Id::create(),
            $title,
            new Team(Id::create(), $teamName),
            $command->startDate(),
            $command->endDate(),
            PositiveNumber::create($nextSprintNumber)
        );

        $sprintWidget = new SprintWidget($this->sprintRepository);
        $sprintWidget->addNewSprint($sprint);
    }
}
