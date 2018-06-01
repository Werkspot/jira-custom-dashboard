<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\BurndownWidget\Domain;

use DateTimeImmutable;

class SaveRemainingPointsCommand
{
    /** @var string */
    private $sprintId;

    /** @var DateTimeImmutable */
    private $date;

    /** @var int */
    private $value;

    public function __construct(string $sprintId, DateTimeImmutable $date, int $value)
    {
        $this->sprintId = $sprintId;
        $this->date = $date;
        $this->value = $value;
    }

    public function sprintId(): string
    {
        return $this->sprintId;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function value(): int
    {
        return $this->value;
    }
}
