<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BurndownWidget\Domain;

use Symfony\Component\HttpFoundation\Response;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Widget\WidgetInterface;

final class BurndownWidget implements WidgetInterface
{
    /** @var SprintRepositoryInterface */
    private $sprintRepository;

    /** @var RemainingPointsRepositoryInterface */
    private $remainingPointsRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository, RemainingPointsRepositoryInterface $remainingPointsRepository)
    {
        $this->sprintRepository = $sprintRepository;
        $this->remainingPointsRepository = $remainingPointsRepository;
    }

    /**
     * @throws EntityNotFoundException
     * @return RemainingPoints[]
     */
    public function getRemainingPointsBySprint(Sprint $sprint): array
    {
        return $this->remainingPointsRepository->findBySprint($sprint);
    }

    public function saveRemainingPoints(RemainingPoints $remainingPoints): void
    {
        $this->remainingPointsRepository->upsert($remainingPoints);
    }

    public function render(): Response
    {
        return new Response('RemainingPoints Widget');
    }
}
