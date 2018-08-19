<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\TeamWidget\Application;

use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\TeamRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;
use Werkspot\JiraDashboard\TeamWidget\Domain\AddTeamCommand;
use Werkspot\JiraDashboard\TeamWidget\Domain\TeamWidget;

class AddTeamCommandHandler
{
    /**
     * @var TeamRepositoryInterface
     */
    private $teamRepository;

    public function __construct(TeamRepositoryInterface $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function handle(AddTeamCommand $command): void
    {
        $teamName = ShortText::create($command->teamName());

        $team = new Team(Id::create(), $teamName);

        $teamWidget = new TeamWidget($this->teamRepository);
        $teamWidget->addTeam($team);
    }
}
