<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\TeamWidget\Domain;

class GetSprintsQuery
{
    /**
     * @var string
     */
    private $teamId;

    public function __construct(string $teamId)
    {
        $this->teamId = $teamId;
    }

    public function getTeamId(): string
    {
        return $this->teamId;
    }
}
