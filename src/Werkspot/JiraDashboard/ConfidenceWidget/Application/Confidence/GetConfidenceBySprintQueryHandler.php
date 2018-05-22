<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Application\Confidence;

use Werkspot\JiraDashboard\ConfidenceWidget\Domain\ConfidenceRepositoryInterface;
use Werkspot\JiraDashboard\ConfidenceWidget\Domain\GetConfidenceBySprintQuery;
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

    public function handle(GetConfidenceBySprintQuery $confidenceBySprintQuery): ?array
    {
        $sprint = $this->sprintRepository->find(Id::create($confidenceBySprintQuery->sprintId()));

        $confidenceCollection = $this->confidenceRepository->findBySprint($sprint);

        return $confidenceCollection;
    }
}
