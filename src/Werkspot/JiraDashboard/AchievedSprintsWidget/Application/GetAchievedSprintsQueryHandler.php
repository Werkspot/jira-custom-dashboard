<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\AchievedSprintsWidget\Application;

use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\AchievedSprintsWidget;
use Werkspot\JiraDashboard\AchievedSprintsWidget\Domain\GetAchievedSprintsQuery;
use Werkspot\JiraDashboard\SharedKernel\Domain\Exception\EntityNotFoundException;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\SprintRepositoryInterface;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

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
    public function handle(GetAchievedSprintsQuery $getAchievedSprintsQuery): ?array
    {
        $achievedSprintsWidget = new AchievedSprintsWidget($this->sprintRepository);
        $achievedSprints = $achievedSprintsWidget->getAchievedSprints(Id::create($getAchievedSprintsQuery->teamId()));

        // @todo refactor to serialize on a GraphQLType
        return [
            'achieved' => $achievedSprints->getAchieved(),
            'total' => $achievedSprints->getTotal()
        ];
    }
}
