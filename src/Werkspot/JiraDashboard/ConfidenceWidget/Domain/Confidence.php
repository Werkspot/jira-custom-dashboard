<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Domain;

use DateTimeImmutable;

final class Confidence
{
    /** @var DateTimeImmutable */
    private $date;

    /** @var ConfidenceValueEnum */
    private $value;

    public function __construct(DateTimeImmutable $date, ConfidenceValueEnum $value)
    {
        $this->date = $date;
        $this->value = $value;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getValue(): ConfidenceValueEnum
    {
        return $this->value;
    }
}
