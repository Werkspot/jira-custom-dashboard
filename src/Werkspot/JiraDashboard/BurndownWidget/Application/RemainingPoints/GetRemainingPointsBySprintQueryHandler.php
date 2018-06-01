<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BurndownWidget\Application\RemainingPoints;

use Werkspot\JiraDashboard\BurndownWidget\Domain\BurndownWidget;
use Werkspot\JiraDashboard\BurndownWidget\Domain\GetRemainingPointsBySprintQuery;
use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPointsRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

class GetRemainingPointsBySprintQueryHandler
{
    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    /**
     * @var RemainingPointsRepositoryInterface
     */
    private $remainingPointsRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository, RemainingPointsRepositoryInterface $remainingPointsRepository)
    {
        $this->sprintRepository = $sprintRepository;
        $this->remainingPointsRepository = $remainingPointsRepository;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function handle(GetRemainingPointsBySprintQuery $remainingPointsBySprintQuery): ?array
    {
        $sprint = $this->sprintRepository->find(Id::create($remainingPointsBySprintQuery->sprintId()));

        $remainingPointsWidget = new BurndownWidget($this->sprintRepository, $this->remainingPointsRepository);

        return $remainingPointsWidget->getRemainingPointsBySprint($sprint);
    }
}
