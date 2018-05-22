<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint;

use DateTimeImmutable;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;

class Sprint
{
    /** @var Id */
    private $id;

    /** @var string */
    private $name;

    /** @var ShortText */
    private $title;

    /** @var Team */
    private $team;

    /** @var DateTimeImmutable */
    private $startDate;

    /** @var DateTimeImmutable */
    private $endDate;

    public function __construct(Id $id, ShortText $title, Team $team, DateTimeImmutable $startDate, DateTimeImmutable $endDate)
    {
        $this->id = $id;
        $this->name = strtolower(str_replace(' ', '-', $title));
        $this->title = $title;
        $this->team = $team;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTitle(): ShortText
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
