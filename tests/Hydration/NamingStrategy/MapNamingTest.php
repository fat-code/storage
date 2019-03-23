<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\NamingStrategy;

use FatCode\Storage\Hydration\NamingStrategy\MapNaming;
use PHPUnit\Framework\TestCase;

final class MapNamingTest extends TestCase
{
    public function testMap() : void
    {
        $namingStrategy = new MapNaming(['mappedName' => 'mapped_name', 'otherName' => 'other_name']);

        self::assertSame('mapped_name', $namingStrategy->map('mappedName'));
        self::assertSame('other_name', $namingStrategy->map('otherName'));
        self::assertSame('unmapped_name', $namingStrategy->map('unmapped_name'));
    }
}
