<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\TeamWidget\Domain;

class AddTeamCommand
{
    /**
     * @var string
     */
    private $teamName;

    public function __construct(string $teamName)
    {
        $this->teamName = $teamName;
    }

    public function teamName(): string
    {
        return $this->teamName;
    }
}
