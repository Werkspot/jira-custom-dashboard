<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\AchievedSprintsWidget\Application;

use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\AchievedSprintsWidget;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;

class GetAchievedSprintsQueryHandler
{
    /**
     * @var SprintRepositoryInterface
     */
    private $sprintRepository;

    public function __construct(SprintRepositoryInterface $sprintRepository)
    {
        $this->sprintRepository = $sprintRepository;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function handle(): ?array
    {
        $achievedSprintsWidget = new AchievedSprintsWidget($this->sprintRepository);
        $achievedSprints = $achievedSprintsWidget->getAchievedSprints();

        // @todo refactor to serialize on a GraphQLType
        return [
            'achieved' => $achievedSprints->getAchieved(),
            'total' => $achievedSprints->getTotal()
        ];
    }
}
