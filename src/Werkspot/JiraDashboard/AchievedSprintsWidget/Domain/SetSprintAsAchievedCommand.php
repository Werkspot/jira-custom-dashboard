<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\AchievedSprintsWidget\Domain;

use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

class SetSprintAsAchievedCommand
{
    /**
     * @var Id
     */
    private $sprintId;

    /**
     * @var bool
     */
    private $achieved;

    public function __construct(Id $sprintId, bool $achieved)
    {
        $this->sprintId = $sprintId;
        $this->achieved = $achieved;
    }

    public function sprintId(): Id
    {
        return $this->sprintId;
    }

    public function isAchieved(): bool
    {
        return $this->achieved;
    }
}
