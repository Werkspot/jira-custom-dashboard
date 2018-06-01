<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SharedKernel\Unit\GraphQL\Type;

use PHPUnit\Framework\TestCase;
use GraphQL\Language\AST\Node;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\PositiveNumber;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\GraphQL\Type\PositiveNumberType;

class PositiveNumberTypeTest extends TestCase
{
    /**
     * @test
     */
    public function serialize_whenThereIsPositiveNumber_shouldSerializeInInteger()
    {
        $number = 10;
        $valueObject = PositiveNumber::create(10);
        $this->assertEquals(
            $number,
            PositiveNumber::create($number)->number()
        );
    }

    /**
     * @test
     */
    public function parseValue_whenThereIsInteger_shouldParseToValueObjectPositiveNumber()
    {
        $number = 100;
        $this->assertEquals(
            $number,
            PositiveNumberType::parseValue($number)->number()
        );
    }

    /**
     * @test
     */
    public function parseLiteral_whenThereIsANode_shouldParseToValueObjectPositiveNumber()
    {
        $number = 100;

        $nodeStub = $this->createMock(Node::class);
        $nodeStub->value = $number;

        $this->assertEquals(
            $number,
            PositiveNumberType::parseLiteral($nodeStub)->number()
        );
    }
}
