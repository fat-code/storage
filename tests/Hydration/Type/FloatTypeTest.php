<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Type;

use FatCode\Storage\Hydration\Type\FloatType;
use PHPUnit\Framework\TestCase;

final class FloatTypeTest extends TestCase
{
    /**
     * @param $given
     * @param $expected
     * @dataProvider provideExtractionValues
     */
    public function testExtract($given, $expected) : void
    {
        $type = new FloatType();
        self::assertEquals($expected, $type->extract($given));
    }

    /**
     * @param $given
     * @param $expected
     * @dataProvider provideHydrationValues
     */
    public function testHydrate($given, $expected) : void
    {
        $type = new FloatType();
        self::assertEquals($expected, $type->hydrate($given));
    }

    public function provideExtractionValues() : array
    {
        return [
            [10.21, 10.21],
            [10.989, 10.989],
            [99999999.999, 99999999.999],
            [10, 10.00],
            ['10.202', 10.202],
            ['10.00', 10.00],
            ['10', 10.00],
        ];
    }

    public function provideHydrationValues() : array
    {
        return [
            [10.21, 10.21],
            [10.989, 10.989],
            [99999999.999, 99999999.999],
            [10, 10.00],
            ['10.202', 10.202],
            ['10.00', 10.00],
            ['10', 10.00],
        ];
    }
}
