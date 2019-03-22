<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Type;

use FatCode\Storage\Hydration\Type\BooleanType;
use PHPUnit\Framework\TestCase;

final class BooleanTypeTest extends TestCase
{
    /**
     * @param $given
     * @param bool $expected
     * @dataProvider provideExtractionValues
     */
    public function testExtract($given, bool $expected) : void
    {
        $boolean = new BooleanType();
        self::assertSame($expected, $boolean->extract($given));
    }

    /**
     * @param $given
     * @param bool $expected
     * @dataProvider provideHydrationValues
     */
    public function testHydrate($given, bool $expected) : void
    {
        $boolean = new BooleanType();
        self::assertSame($expected, $boolean->hydrate($given));
    }

    public function provideExtractionValues() : array
    {
        return [
            [true, true],
            [false, false],
            [0, false],
            [1, true],
            ['false', true],
            ['true', true],
        ];
    }

    public function provideHydrationValues() : array
    {
        return [
            [true, true],
            [false, false],
            [0, false],
            [1, true],
            ['false', true],
            ['true', true],
        ];
    }
}
