<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\CapacityVelocityWidget\Domain;

use Symfony\Component\HttpFoundation\Response;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Widget\WidgetInterface;

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

    public function getCapacityOrderedBySprintNumber(): ?array
    {
        $allSprints = $this->sprintRepository->findAllOrderByNumber();

        $capacityCollection = [];
        /** @var Sprint $item */
        foreach ($allSprints as $item) {
            $capacityCollection[] = [$item->getNumber()->number() => $item->getCapacity()];
        }

        return $capacityCollection;

    }

    public function getVelocityOrderedBySprintNumber()
    {

    }

    public function setSprintCapacity(Sprint $sprint): void
    {

    }

    public function setSprintVelocity(Sprint $sprint): void
    {

    }

    public function render(): Response
    {
        return new Response('Capacity Velocity Widget');
    }
}
