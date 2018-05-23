<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Domain;

use DateTimeImmutable;

class SaveConfidenceCommand
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

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function value(): ConfidenceValueEnum
    {
        return $this->value;
    }
}
