<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint;

use DateTimeImmutable;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;

final class Sprint
{
    /** @var SprintId */
    private $id;

    /** @var string */
    private $name;

    /** @var SprintTitle */
    private $title;

    /** @var Team */
    private $team;

    /** @var DateTimeImmutable */
    private $startDate;

    /** @var DateTimeImmutable */
    private $endDate;

    public function __construct(SprintId $sprintId, SprintTitle $title, Team $team, DateTimeImmutable $startDate, DateTimeImmutable $endDate)
    {
        $this->id = $sprintId;
        $this->name = strtolower(str_replace(' ', '-', $title));
        $this->title = $title;
        $this->team = $team;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getId(): SprintId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTitle(): SprintTitle
    {
        return $this->title;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }
}
