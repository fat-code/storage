<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Property;

use FatCode\Storage\Hydration\NamingStrategy\MapNaming;
use PHPUnit\Framework\TestCase;

final class MapNamingTest extends TestCase
{
    public function testHydrate() : void
    {
        $namingStrategy = new MapNaming(['mapped_name' => 'mappedName', 'other_name' => 'otherName']);

        self::assertSame('mappedName', $namingStrategy->hydrate('mapped_name'));
        self::assertSame('otherName', $namingStrategy->hydrate('other_name'));
        self::assertSame('unmapped_name', $namingStrategy->hydrate('unmapped_name'));
    }

    public function testExtract() : void
    {
        $namingStrategy = new MapNaming(['mapped_name' => 'mappedName', 'other_name' => 'otherName']);

        self::assertSame('mapped_name', $namingStrategy->extract('mappedName'));
        self::assertSame('other_name', $namingStrategy->extract('otherName'));
        self::assertSame('unmapped_name', $namingStrategy->extract('unmapped_name'));
    }
}
