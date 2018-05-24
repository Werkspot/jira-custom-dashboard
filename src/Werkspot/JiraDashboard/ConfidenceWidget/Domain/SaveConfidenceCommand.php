<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Domain;

use DateTime;

class SaveConfidenceCommand
{
    /** @var DateTime */
    private $date;

    /** @var string */
    private $value;

    public function __construct(DateTime $date, string $value)
    {
        $this->date = $date;
        $this->value = $value;
    }

    public function date(): DateTime
    {
        return $this->date;
    }

    public function value(): string
    {
        return $this->value;
    }
}
