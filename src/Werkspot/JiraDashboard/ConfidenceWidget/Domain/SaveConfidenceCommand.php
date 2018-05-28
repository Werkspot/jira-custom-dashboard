<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Domain;

use DateTimeImmutable;

class SaveConfidenceCommand
{
    /** @var DateTimeImmutable */
    private $date;

    /** @var int */
    private $value;

    public function __construct(DateTimeImmutable $date, int $value)
    {
        $this->date = $date;
        $this->value = $value;
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
