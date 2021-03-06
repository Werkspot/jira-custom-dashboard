<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\AchievedSprintsWidget\Domain;

class GetAchievedSprintsQuery
{
    /**
     * @var string
     */
    private $teamId;

    public function __construct(string $teamId)
    {
        $this->teamId = $teamId;
    }

    public function teamId(): string
    {
        return $this->teamId;
    }
}
