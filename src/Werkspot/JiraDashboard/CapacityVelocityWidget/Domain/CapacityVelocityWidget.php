<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\CapacityVelocityWidget\Domain;

use Symfony\Component\HttpFoundation\Response;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Widget\WidgetInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

class CapacityVelocityWidget implements WidgetInterface
{
    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository)
    {
        $this->sprintRepository = $sprintRepository;
    }

    public function render(): Response
    {
        // we need to get capacity data ordered by sprint number
        // we need to get velocity data ordered by sprint number
        //$capacityCollection = $this->getCapacityOrderedBySprintNumber($teamId);
        //$velocityCollection = $this->getVelocityOrderedBySprintNumber($teamId);

        return new Response('Capacity Velocity Widget');
    }


    public function getCapacityOrderedBySprintNumber(Id $teamId): ?array
    {
        $allSprints = $this->sprintRepository->findAllByTeamOrderByNumber($teamId);

        $capacityCollection = [];
        /** @var Sprint $item */
        foreach ($allSprints as $item) {
            $capacityCollection[] = [$item->getNumber()->number() => $item->getCapacity()];
        }

        return $capacityCollection;

    }

    public function getVelocityOrderedBySprintNumber(Id $teamId): ?array
    {
        $allSprints = $this->sprintRepository->findAllByTeamOrderByNumber($teamId);

        $velocityCollection = [];
        /** @var Sprint $item */
        foreach ($allSprints as $item) {
            $velocityCollection[] = [$item->getNumber()->number() => $item->getVelocity()];
        }

        return $velocityCollection;
    }

    public function setSprintCapacity(Sprint $sprint): void
    {

    }

    public function setSprintVelocity(Sprint $sprint): void
    {

    }


}
