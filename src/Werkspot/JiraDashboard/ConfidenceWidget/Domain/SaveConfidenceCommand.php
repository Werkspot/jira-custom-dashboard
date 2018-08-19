<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Domain;

use DateTimeImmutable;

class SaveConfidenceCommand
{
    /**
     * @var string
     */
    private $sprintId;

    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @var int
     */
    private $value;

    public function __construct(string $sprintId, DateTimeImmutable $date, int $value)
    {
        $this->sprintId = $sprintId;
        $this->date = $date;
        $this->value = $value;
    }

    public function getSprintId(): string
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
