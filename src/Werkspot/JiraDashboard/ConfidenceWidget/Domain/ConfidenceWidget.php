<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Domain;

use Symfony\Component\HttpFoundation\Response;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Widget\WidgetInterface;

final class ConfidenceWidget implements WidgetInterface
{
    /** @var SprintRepositoryInterface */
    private $sprintRepository;

    /** @var ConfidenceRepositoryInterface */
    private $confidenceRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository, ConfidenceRepositoryInterface $confidenceRepository)
    {
        $this->sprintRepository = $sprintRepository;
        $this->confidenceRepository = $confidenceRepository;
    }

    /**
     * @throws EntityNotFoundException
     * @return Confidence[]
     */
    public function getConfidenceBySprint(Sprint $sprint): array
    {
        return $this->confidenceRepository->findBySprint($sprint);
    }

    public function render(): Response
    {
        return new Response('Confidence Widget');
    }
}
