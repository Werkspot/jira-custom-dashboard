<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SharedKernel\Unit\GraphQL\Type;

use PHPUnit\Framework\TestCase;
use GraphQL\Language\AST\Node;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\GraphQL\Type\UuidType;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

class UuidTypeTest extends TestCase
{
    /**
     * @test
     */
    public function serialize_whenThereIsValueObjectId_shouldSerializeInAString()
    {
        $uuid = '55fab481-1059-47ca-a0f1-398202411a21';
        $valueObject = Id::create($uuid);
        $this->assertEquals(
            $uuid,
            UuidType::serialize($valueObject)
        );
    }

    /**
     * @test
     */
    public function parseValue_whenThereIsUuidString_shouldParseToValueObjectId()
    {
        $uuid = 'f87c0b82-0cde-4d6b-9539-101c832a7085';
        $this->assertEquals(
            $uuid,
            UuidType::parseValue($uuid)->id()
        );
    }

    /**
     * @test
     */
    public function parseLiteral_whenThereIsUuidString_shouldParseToValueObjectId()
    {
        $uuid = 'f87c0b82-0cde-4d6b-9539-101c832a7085';

        $nodeStub = $this->createMock(Node::class);
        $nodeStub->value = $uuid;

        $this->assertEquals(
            $uuid,
            UuidType::parseLiteral($nodeStub)->id()
        );
    }
}
