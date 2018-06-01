<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\GraphQL\Type;

use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\Type;

class DateTimeType extends Type
{
    public static function serialize(\DateTimeImmutable $value): string
    {
        return $value->format(\DateTime::ATOM);
    }

    public static function parseValue(string $value): \DateTimeImmutable
    {
        return new \DateTimeImmutable($value);
    }

    public static function parseLiteral(Node $valueNode): \DateTimeImmutable
    {
        return new \DateTimeImmutable($valueNode->value);
    }
}
