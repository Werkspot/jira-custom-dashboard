<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\GraphQL\Type;

use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\Type;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;

class ShortTextType extends Type
{
    public static function serialize(ShortText $value): string
    {
        return $value->title();
    }

    public static function parseValue(string $value): ShortText
    {
        return ShortText::create($value);
    }

    public static function parseLiteral(Node $valueNode): ShortText
    {
        return ShortText::create($valueNode->value);
    }
}
