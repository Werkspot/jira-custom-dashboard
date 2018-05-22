<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Domain;

use DateTimeImmutable;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

class Confidence
{
    /** @var Id */
    private $id;

    /** @var DateTimeImmutable */
    private $date;

    /** @var ConfidenceValueEnum */
    private $value;

    public function __construct(DateTimeImmutable $date, ConfidenceValueEnum $value)
    {
        $this->id = Id::create();
        $this->date = $date;
        $this->value = $value;
    }

    public function getId(): Id
    {
        return $this->id;
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
