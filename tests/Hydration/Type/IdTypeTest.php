<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Type;

use FatCode\Storage\Hydration\Type\IdType;
use PHPUnit\Framework\TestCase;

final class IdTypeTest extends TestCase
{
    public function testExtract() : void
    {
        $type = new IdType();
        self::assertSame(1, $type->extract(1));
    }

    public function testHydrate() : void
    {
        $type = new IdType();
        self::assertEquals(1, $type->hydrate(1));
    }
}
