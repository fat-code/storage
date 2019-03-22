<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Type;

use FatCode\Storage\Hydration\Type\DateTimeType;
use PHPUnit\Framework\TestCase;
use DateTime;
use DateTimeZone;

final class DateTimeTypeTest extends TestCase
{
    /**
     * @param $given
     * @param $expected
     * @dataProvider provideExtractionValues
     */
    public function testExtract($given, $expected) : void
    {
        $type = new DateTimeType();
        self::assertEquals($expected, $type->extract($given));
    }

    /**
     * @param $given
     * @param $expected
     * @dataProvider provideHydrationValues
     */
    public function testHydrate($given, $expected) : void
    {
        $type = new DateTimeType();
        self::assertEquals($expected, $type->hydrate($given));
    }

    public function testGetKeys() : void
    {
        $type = new DateTimeType();
        $keys = $type->getKeys('test');

        self::assertSame('testDate', $keys[0]);
        self::assertSame('testTime', $keys[1]);
        self::assertSame('testTimezone', $keys[2]);
    }

    public function testNullable() : void
    {
        $type = new DateTimeType();

        self::assertFalse($type->isNullable());
        $type->nullable();
        self::assertTrue($type->isNullable());
    }

    public function provideExtractionValues() : array
    {
        return [
            [new DateTime('@1553240567'), [1553240567, '+00:00']],
            [new DateTime('2012-09-10 14:21:31', new DateTimeZone('Europe/Berlin')), [1347279691, 'Europe/Berlin']],
        ];
    }

    public function provideHydrationValues() : array
    {
        return [
            [[1553240567, '+00:00'], new DateTime('@1553240567')],
            [[1347279691, 'Europe/Berlin'], new DateTime('2012-09-10 14:21:31', new DateTimeZone('Europe/Berlin'))]
        ];
    }
}
