<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Property;

use FatCode\Storage\Hydration\NamingStrategy\DirectNaming;
use PHPUnit\Framework\TestCase;

final class DirectNamingTest extends TestCase
{
    public function testExtract() : void
    {
        $namingStrategy = new DirectNaming();
        self::assertSame('test1', $namingStrategy->extract('test1'));
    }

    public function testHydrate() : void
    {
        $namingStrategy = new DirectNaming();
        self::assertSame('test1', $namingStrategy->hydrate('test1'));
    }
}
