<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject;

use InvalidArgumentException;

class AbsoluteNumber
{
    /** @var int */
    protected $number;

    protected function __construct(int $number)
    {
        if ($number <= 0) {
           throw new InvalidArgumentException();
        }

        $this->number = abs($number);
    }

    public static function create(int $number): self
    {
        return new static($number);
    }

    public function number(): int
    {
        return $this->number;
    }
}
