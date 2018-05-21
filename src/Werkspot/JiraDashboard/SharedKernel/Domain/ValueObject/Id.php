<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject;

use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

class Id
{
    /** @var string */
    protected $id;

    protected function __construct(string $id = null)
    {
        if (!empty($id)) {
            if (!Uuid::isValid($id)) {
                throw new InvalidUuidStringException();
            }
            $this->id = $id;
            return;
        }

        $this->id = Uuid::uuid4()->toString();
    }

    public static function create(string $id = null): self
    {
        return new static($id);
    }

    public function id(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
