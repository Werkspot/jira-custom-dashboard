<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint;

use DateTimeImmutable;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\AbsoluteNumber;
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

    /** @var AbsoluteNumber */
    private $number;

    public function __construct(Id $id, ShortText $title, Team $team, DateTimeImmutable $startDate, DateTimeImmutable $endDate, AbsoluteNumber $number)
    {
        $this->id = $id;
        $this->name = strtolower(str_replace(' ', '-', $title));
        $this->title = $title;
        $this->team = $team;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->number = $number;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setTitle(ShortText $title): Sprint
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }

    public function setTeam(Team $team): Sprint
    {
        $this->team = $team;
        return $this;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function setStartDate(DateTimeImmutable $startDate): Sprint
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setEndDate(DateTimeImmutable $endDate): Sprint
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setNumber(AbsoluteNumber $number): Sprint
    {
        $this->number = $number;
        return $this;
    }

    public function getNumber(): AbsoluteNumber
    {
        return $this->number;
    }
}
