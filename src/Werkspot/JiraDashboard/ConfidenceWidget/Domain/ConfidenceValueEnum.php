<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Domain;

final class ConfidenceValueEnum
{
    private const ZERO = 0;
    private const ONE = 1;
    private const TWO = 2;
    private const THREE = 3;
    private const FOUR = 4;
    private const FIVE = 5;

    private static $validValues = [
        self::ZERO,
        self::ONE,
        self::TWO,
        self::THREE,
        self::FOUR,
        self::FIVE,
    ];

    /** @var int */
    private $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function create(int $value): self
    {
        if (!in_array($value, self::$validValues)) {
            throw new \InvalidArgumentException();
        }

        return new static($value);
    }

    public static function zero(): self
    {
        return new self(self::ZERO);
    }

    public static function one(): self
    {
        return new self(self::ONE);
    }

    public static function two(): self
    {
        return new self(self::TWO);
    }

    public static function three(): self
    {
        return new self(self::THREE);
    }

    public static function four(): self
    {
        return new self(self::FOUR);
    }

    public static function five(): self
    {
        return new self(self::FIVE);
    }

    public function __invoke(): int
    {
        return $this->value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value();
    }
}
