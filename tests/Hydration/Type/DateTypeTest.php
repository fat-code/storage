<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Type;

use FatCode\Storage\Hydration\Type\DateType;
use PHPUnit\Framework\TestCase;
use DateTime;
use DateTimeZone;

final class DateTypeTest extends TestCase
{
    /**
     * @param $given
     * @param $expected
     * @dataProvider provideExtractionValues
     */
    public function testExtract($given, $expected) : void
    {
        $type = new DateType();
        self::assertEquals($expected, $type->extract($given));
    }

    /**
     * @param $given
     * @param $expected
     * @dataProvider provideHydrationValues
     */
    public function testHydrate($given, $expected) : void
    {
        $type = new DateType();
        $expected->setTime(0, 0, 0);
        self::assertEquals($expected, $type->hydrate($given));
    }

    public function provideExtractionValues() : array
    {
        return [
            [new DateTime('@1553240567'), 20190322],
            [new DateTime('2012-09-10 14:21:31'), 20120910],
            ['2018-12-12', null]
        ];
    }

    public function provideHydrationValues() : array
    {
        return [
            [20120910, new DateTime('2012-09-10')],
            [20190322, new DateTime('@1553240567')]
        ];
    }
}
