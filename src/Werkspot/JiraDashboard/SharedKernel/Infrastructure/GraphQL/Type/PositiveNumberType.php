<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\GraphQL\Type;

use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\Type;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;

class PositiveNumberType extends Type
{
    public static function serialize(PositiveNumber $value): int
    {
        return $value->number();
    }

    public static function parseValue(int $value): PositiveNumber
    {
        return PositiveNumber::create($value);
    }

    public static function parseLiteral(Node $valueNode): PositiveNumber
    {
        return PositiveNumber::create($valueNode->value);
    }
}
