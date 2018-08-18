<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint;

use DateTimeImmutable;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Team\Team;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;
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

    /** @var PositiveNumber */
    private $number;

    /** @var float */
    private $capacity;

    /** @var int */
    private $velocity;

    /** @var bool */
    private $achieved;

    public function __construct(Id $id, ShortText $title, Team $team, DateTimeImmutable $startDate, DateTimeImmutable $endDate, PositiveNumber $number)
    {
        $this->id = $id;
        $this->name = strtolower(str_replace(' ', '-', $title));
        $this->title = $title;
        $this->team = $team;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->number = $number;
        $this->capacity = null;
        $this->velocity = null;
        $this->achieved = false;
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

    public function setNumber(PositiveNumber $number): Sprint
    {
        $this->number = $number;
        return $this;
    }

    public function getNumber(): PositiveNumber
    {
        return $this->number;
    }

    public function setAchieved(bool $achieved): Sprint
    {
        $this->achieved = $achieved;
        return $this;
    }

    public function getCapacity(): float
    {
        return $this->capacity;
    }

    public function setCapacity(float $capacity): Sprint
    {
        $this->capacity = $capacity;
        return $this;
    }

    public function getVelocity(): int
    {
        return $this->velocity;
    }

    public function setVelocity(int $velocity): Sprint
    {
        $this->velocity = $velocity;
        return $this;
    }

    public function isAchieved(): bool
    {
        return $this->achieved;
    }

    public function getPeriod(): \DatePeriod
    {
        $sprintPeriod = new \DatePeriod(
            new \DateTime($this->getStartDate()->format('Y-m-d')),
            new \DateInterval('P1D'),
            new \DateTime($this->getEndDate()->format('Y-m-d'))
        );

        return $sprintPeriod;
    }
}
