<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Application\Confidence;

use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceRepositoryInterface;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceWidget;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\GetConfidenceBySprintQuery;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

class GetConfidenceBySprintQueryHandler
{
    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    /**
     * @var ConfidenceRepositoryInterface
     */
    private $confidenceRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository, ConfidenceRepositoryInterface $confidenceRepository)
    {
        $this->sprintRepository = $sprintRepository;
        $this->confidenceRepository = $confidenceRepository;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function handle(GetConfidenceBySprintQuery $confidenceBySprintQuery): ?array
    {
        $sprint = $this->sprintRepository->findActive();

        $confidenceWidget = new ConfidenceWidget($this->sprintRepository, $this->confidenceRepository);

        return $confidenceWidget->getConfidenceBySprint($sprint);
    }
}
