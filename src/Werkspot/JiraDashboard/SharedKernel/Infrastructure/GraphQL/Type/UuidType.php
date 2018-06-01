<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\GraphQL\Type;

use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\Type;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

class UuidType extends Type
{
    public static function serialize(Id $value): string
    {
        return $value->id();
    }

    public static function parseValue(string $value): Id
    {
        return Id::create($value);
    }

    public static function parseLiteral(Node $valueNode): Id
    {
        return Id::create($valueNode->value);
    }
}
