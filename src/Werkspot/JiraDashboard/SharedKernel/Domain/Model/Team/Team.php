<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team;

final class Team
{
    /** @var TeamId */
    private $id;

    /** @var TeamName */
    private $name;

    public function __construct(TeamId $teamId, TeamName $name)
    {
        $this->id = $teamId;
        $this->name = $name;
    }

    public function getId(): TeamId
    {
        return $this->id;
    }

    public function getName(): TeamName
    {
        return $this->name;
    }
}
