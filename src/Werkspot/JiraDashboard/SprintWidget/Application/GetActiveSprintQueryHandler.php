<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SprintWidget\Application;

use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SprintWidget\Domain\SprintWidget;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;

class GetActiveSprintQueryHandler
{
    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository)
    {
        $this->sprintRepository = $sprintRepository;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function handle(): Sprint
    {
        $sprintWidget = new SprintWidget($this->sprintRepository);

        return $sprintWidget->getActiveSprint();
    }
}
