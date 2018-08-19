<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SprintWidget\Domain;

use DateTimeImmutable;

class AddNewSprintCommand
{
    /**
     * @var string
     */
    private $teamId;

    /**
     * @var DateTimeImmutable
     */
    private $startDate;

    /**
     * @var DateTimeImmutable
     */
    private $endDate;

    public function __construct(string $teamId, DateTimeImmutable $startDate, DateTimeImmutable $endDate)
    {
        $this->teamId = $teamId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function teamId(): string
    {
        return $this->teamId;
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
