<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Type;

use FatCode\Storage\Hydration\Type\IntegerType;
use PHPUnit\Framework\TestCase;

final class IntegerTypeTest extends TestCase
{
    /**
     * @param $given
     * @param $expected
     * @dataProvider provideExtractionValues
     */
    public function testExtract($given, $expected) : void
    {
        $type = new IntegerType();
        self::assertEquals($expected, $type->extract($given));
    }

    /**
     * @param $given
     * @param $expected
     * @dataProvider provideHydrationValues
     */
    public function testHydrate($given, $expected) : void
    {
        $type = new IntegerType();
        self::assertEquals($expected, $type->hydrate($given));
    }

    public function provideExtractionValues() : array
    {
        return [
            [10.21, 10],
            ['10', 10],
            [9, 9],
        ];
    }

    public function provideHydrationValues() : array
    {
        return [
            [10.21, 10],
            ['10', 10],
            [9, 9],
        ];
    }
}
