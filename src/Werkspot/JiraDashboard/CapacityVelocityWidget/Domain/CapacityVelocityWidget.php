<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\CapacityVelocityWidget\Domain;

use function array_filter;
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

        $capacityCollection = array_filter($allSprints, function (Sprint $sprint) {
            return [
                $sprint->getNumber()->number() => $sprint->getCapacity()
            ];
        });

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
