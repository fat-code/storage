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
        self::assertSame('id', $type->getLocalName());
        self::assertSame('_id', $type->getExternalName());
    }

    public function testHydrate() : void
    {
        $type = new IdType('id', 'id');
        self::assertEquals(1, $type->hydrate(1));
        self::assertSame('id', $type->getLocalName());
        self::assertSame('id', $type->getExternalName());
    }
}
