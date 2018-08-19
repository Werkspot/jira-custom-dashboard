<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Domain;

class GetConfidenceBySprintQuery
{
    /**
     * @var string
     */
    private $sprintId;

    public function __construct(string $sprintId)
    {
        $this->sprintId = $sprintId;
    }

    public function sprintId(): string
    {
        return $this->sprintId;
    }
}
