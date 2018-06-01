<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BurndownWidget\Application\RemainingPoints;

use Werkspot\JiraDashboard\BurndownWidget\Domain\BurndownWidget;
use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPoints;
use Werkspot\JiraDashboard\BurndownWidget\Domain\RemainingPointsRepositoryInterface;
use Werkspot\JiraDashboard\BurndownWidget\Domain\SaveRemainingPointsCommand;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;

class SaveRemainingPointsCommandHandler
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
        $this->remainingPointsRepository = $remainingPointsRepository;
        $this->sprintRepository = $sprintRepository;
    }

    public function handle(SaveRemainingPointsCommand $command): void
    {
        $sprint = $this->sprintRepository->find(Id::create($command->sprintId()));

        if (!$remainingPoints = $this->remainingPointsRepository->findByDate($command->date())) {
            $remainingPoints = new RemainingPoints(
                $sprint,
                $command->date(),
                PositiveNumber::create($command->value())
            );
        }
        $remainingPoints->setValue(PositiveNumber::create($command->value()));

        $remainingPointsWidget = new BurndownWidget($this->sprintRepository, $this->remainingPointsRepository);
        $remainingPointsWidget->saveRemainingPoints($remainingPoints);
    }
}
