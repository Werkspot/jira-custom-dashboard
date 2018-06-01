<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SharedKernel\Unit\GraphQL\Type;

use PHPUnit\Framework\TestCase;
use GraphQL\Language\AST\Node;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\ShortText;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\GraphQL\Type\ShortTextType;

class ShortTextTypeTest extends TestCase
{
    /**
     * @test
     */
    public function serialize_whenThereIsShortText_shouldSerializeInString()
    {
        $text = 'Awesome title!';
        $valueObject = ShortText::create($text);
        $this->assertEquals(
            $text,
            ShortText::create($text)->title()
        );
    }

    /**
     * @test
     */
    public function parseValue_whenThereIsString_shouldParseToValueObjectShortText()
    {
        $text = 'Awesome title!';
        $this->assertEquals(
            $text,
            ShortTextType::parseValue($text)->title()
        );
    }

    /**
     * @test
     */
    public function parseLiteral_whenThereIsANode_shouldParseToValueObjectShortText()
    {
        $text = 'Awesome title!';

        $nodeStub = $this->createMock(Node::class);
        $nodeStub->value = $text;

        $this->assertEquals(
            $text,
            ShortTextType::parseLiteral($nodeStub)->title()
        );
    }
}
