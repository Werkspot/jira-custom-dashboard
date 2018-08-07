<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\AchievedSprintsWidget\Domain;

use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;

class AchievedSprints
{
    /** @var PositiveNumber */
    private $achieved;

    /** @var PositiveNumber */
    private $total;

    public function __construct(PositiveNumber $achieved, PositiveNumber $total)
    {
        $this->achieved = $achieved;
        $this->total = $total;
    }

    public function getAchieved(): PositiveNumber
    {
        return $this->achieved;
    }

    public function getTotal(): PositiveNumber
    {
        return $this->total;
    }
}
