<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BurndownWidget\Domain;

use DateTimeImmutable;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;

class RemainingPoints
{
    /** @var Id */
    private $id;

    /** @var Sprint */
    private $sprint;

    /** @var DateTimeImmutable */
    private $date;

    /** @var PositiveNumber */
    private $value;

    public function __construct(Sprint $sprint, DateTimeImmutable $date, PositiveNumber $value)
    {
        $this->id = Id::create();
        $this->sprint = $sprint;
        $this->date = $date;
        $this->value = $value;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setValue(PositiveNumber $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): PositiveNumber
    {
        return $this->value;
    }

    public function setSprint(Sprint $sprint): self
    {
        $this->sprint = $sprint;

        return $this;
    }

    public function getSprint(): Sprint
    {
        return $this->sprint;
    }
}
