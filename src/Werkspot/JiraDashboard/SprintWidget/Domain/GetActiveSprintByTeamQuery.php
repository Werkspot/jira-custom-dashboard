<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SprintWidget\Domain;

class GetActiveSprintByTeamQuery
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
