<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\CapacityVelocityWidget\Application;

use Werkspot\JiraDashboard\CapacityVelocityWidget\Domain\CapacityVelocityWidget;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;

class GetCapacityQueryHandler
{
    /** @var SprintRepositoryInterface */
    private $sprintRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository)
    {
        $this->sprintRepository = $sprintRepository;
    }

    public function handle(): ?array
    {
        $capacityVelocityWidget = new CapacityVelocityWidget($this->sprintRepository);

        $capacityOrderedBySprintNumber = $capacityVelocityWidget->getCapacityOrderedBySprintNumber();

        return $capacityOrderedBySprintNumber;
    }
}
