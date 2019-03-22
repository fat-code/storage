<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Type;

use FatCode\Storage\Exception\TypeException;
use FatCode\Storage\Hydration\Type\DecimalType;
use PHPUnit\Framework\TestCase;

final class DecimalTypeTest extends TestCase
{
    /**
     * @param $given
     * @param $expected
     * @dataProvider provideExtractionValues
     */
    public function testExtract($given, $expected) : void
    {
        $type = new DecimalType();
        self::assertEquals($expected, $type->extract($given));
    }

    /**
     * @param $given
     * @param $expected
     * @dataProvider provideHydrationValues
     */
    public function testHydrate($given, $expected) : void
    {
        $type = new DecimalType();
        self::assertEquals($expected, $type->hydrate($given));
    }

    public function testFailOnInvalidInstantiation() : void
    {
        $this->expectException(TypeException::class);
        new DecimalType(10, 2);
    }

    public function provideExtractionValues() : array
    {
        return [
            [10.21, '10.21'],
            [10.989, '10.98'],
            [99999999.999, '99999999.99'],
            [10, '10.00'],
            ['10.202', '10.20'],
            ['10.00', '10.00'],
            ['10', '10.00'],
        ];
    }

    public function provideHydrationValues() : array
    {
        return [
            [10.21, '10.21'],
            [10.989, '10.98'],
            [99999999.999, '99999999.99'],
            [10, '10.00'],
            ['10.202', '10.20'],
            ['10.00', '10.00'],
            ['10', '10.00'],
        ];
    }
}
