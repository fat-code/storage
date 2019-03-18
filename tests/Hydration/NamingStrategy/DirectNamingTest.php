<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Property;

use FatCode\Storage\Hydration\NamingStrategy\DirectNaming;
use PHPUnit\Framework\TestCase;

class DirectNamingTest extends TestCase
{
    public function testMap() : void
    {
        $namingStrategy = new DirectNaming();
        self::assertSame('test1', $namingStrategy->map('test1'));
    }
}
