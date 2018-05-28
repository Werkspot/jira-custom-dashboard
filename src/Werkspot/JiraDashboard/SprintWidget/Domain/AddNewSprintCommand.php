<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SprintWidget\Domain;

use DateTimeImmutable;

class AddNewSprintCommand
{
    /** @var DateTimeImmutable */
    private $startDate;

    /** @var DateTimeImmutable */
    private $endDate;

    public function __construct(DateTimeImmutable $startDate, DateTimeImmutable $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function startDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate(): DateTimeImmutable
    {
        return $this->endDate;
    }
}
