<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Property;

use FatCode\Storage\Hydration\NamingStrategy\UnderscoreNaming;
use PHPUnit\Framework\TestCase;

final class UnderscoreNamingTest extends TestCase
{
    public function testExtract() : void
    {
        $namingStrategy = new UnderscoreNaming();

        self::assertSame('mapped_name', $namingStrategy->extract('mappedName'));
        self::assertSame('other_name', $namingStrategy->extract('otherName'));
        self::assertSame('unmapped_name', $namingStrategy->extract('unmappedName'));
    }

    public function testHydrate() : void
    {
        $namingStrategy = new UnderscoreNaming();

        self::assertSame('mappedName', $namingStrategy->hydrate('mapped_name'));
        self::assertSame('otherName', $namingStrategy->hydrate('other_name'));
        self::assertSame('unmappedName', $namingStrategy->hydrate('unmapped_name'));
    }
}
