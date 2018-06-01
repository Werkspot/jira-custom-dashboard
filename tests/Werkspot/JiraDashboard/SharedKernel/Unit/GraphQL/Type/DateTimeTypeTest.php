<?php
declare(strict_types=1);

namespace Werkspot\Tests\JiraDashboard\SharedKernel\Unit\GraphQL\Type;

use PHPUnit\Framework\TestCase;
use GraphQL\Language\AST\Node;
use Werkspot\JiraDashboard\SharedKernel\Infrastructure\GraphQL\Type\DateTimeType;

class DateTimeTest extends TestCase
{
    /**
     * @test
     */
    public function serialize_whenThereIsADateTime_shouldSerializeInAStringWithATOMFormat()
    {
        $date = \DateTimeImmutable::createFromFormat('d/m/Y H:i:s', '20/10/2018 13:30:59');
        $this->assertEquals(
            '2018-10-20T13:30:59+00:00',
            DateTimeType::serialize($date)
        );
    }

    /**
     * @test
     */
    public function parseValue_whenThereIsDateTimeString_shouldParseToDateTimeImmutable()
    {
        $date = '2018-10-20T13:30:59+00:00';
        $this->assertEquals(
            $date,
            DateTimeType::parseValue($date)->format(\DateTime::ATOM)
        );
    }

    /**
     * @test
     */
    public function parseLiteral_whenThereIsADateTime_shouldParseToDateTimeImmutable()
    {
        $date = '2018-10-20T13:30:59+00:00';

        $nodeStub = $this->createMock(Node::class);
        $nodeStub->value = $date;

        $this->assertEquals(
            $date,
            DateTimeType::parseLiteral($nodeStub)->format(\DateTime::ATOM)
        );
    }
}
