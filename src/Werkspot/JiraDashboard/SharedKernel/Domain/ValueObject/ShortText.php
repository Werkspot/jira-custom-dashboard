<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject;

use InvalidArgumentException;

class ShortText
{
    public const SHORT_TEXT_MIN_LENGTH = 5;
    public const SHORT_TEXT_MAX_LENGTH = 255;

    /** @var string */
    protected $value;

    protected function __construct(string $value)
    {
        if (
            empty($value) ||
            strlen($value) < self::SHORT_TEXT_MIN_LENGTH
            || strlen($value) > self::SHORT_TEXT_MAX_LENGTH
        ) {
           throw new InvalidArgumentException();
        }

        $this->value = $value;
    }

    public static function create(string $value): self
    {
        return new static($value);
    }

    public function title(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
