<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\NamingStrategy;

use FatCode\Storage\Hydration\NamingStrategy\UnderscoreNaming;
use PHPUnit\Framework\TestCase;

final class UnderscoreNamingTest extends TestCase
{
    public function testMap() : void
    {
        $namingStrategy = new UnderscoreNaming();

        self::assertSame('mapped_name', $namingStrategy->map('mappedName'));
        self::assertSame('other_name', $namingStrategy->map('otherName'));
        self::assertSame('unmapped_name', $namingStrategy->map('unmappedName'));
    }
}
