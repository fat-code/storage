<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Type;

use FatCode\Storage\Hydration\Type\StringType;
use PHPUnit\Framework\TestCase;

final class StringTypeTest extends TestCase
{
    /**
     * @param $given
     * @param $expected
     * @dataProvider provideExtractionValues
     */
    public function testExtract($given, $expected) : void
    {
        $type = new StringType();
        self::assertEquals($expected, $type->extract($given));
    }

    /**
     * @param $given
     * @param $expected
     * @dataProvider provideHydrationValues
     */
    public function testHydrate($given, $expected) : void
    {
        $type = new StringType();
        self::assertEquals($expected, $type->hydrate($given));
    }

    public function provideExtractionValues() : array
    {
        $test = new class {
            public function __toString() : string
            {
                return 'test';
            }
        };
        return [
            [10.21, '10.21'],
            [true, '1'],
            [false, ''],
            [null, ''],
            [10, '10'],
            [$test, 'test'],
        ];
    }

    public function provideHydrationValues() : array
    {
        $test = new class {
            public function __toString() : string
            {
                return 'test';
            }
        };
        return [
            [10.21, '10.21'],
            [true, '1'],
            [false, ''],
            [null, ''],
            [10, '10'],
            [$test, 'test'],
        ];
    }
}
