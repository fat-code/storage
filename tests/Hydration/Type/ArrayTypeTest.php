<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Type;

use FatCode\Storage\Hydration\Type\ArrayType;
use FatCode\Storage\Hydration\Type\IdType;
use FatCode\Storage\Hydration\Type\SerializationMethod;
use PHPUnit\Framework\TestCase;

final class ArrayTypeTest extends TestCase
{
    public function testExtract() : void
    {
        $type = new ArrayType();
        $exampleArray = [1, 2, 3];
        self::assertEquals($exampleArray, $type->extract($exampleArray));

        $type = new ArrayType(SerializationMethod::SERIALIZE());
        self::assertEquals(serialize($exampleArray), $type->extract($exampleArray));

        $type = new ArrayType(SerializationMethod::JSON());
        self::assertEquals(json_encode($exampleArray), $type->extract($exampleArray));
    }

    public function testHydrate() : void
    {
        $type = new ArrayType();
        $exampleArray = [1, 2, 3];
        self::assertEquals($exampleArray, $type->hydrate($exampleArray));

        $type = new ArrayType(SerializationMethod::SERIALIZE());
        self::assertEquals($exampleArray, $type->hydrate(serialize($exampleArray)));

        $type = new ArrayType(SerializationMethod::JSON());
        self::assertEquals($exampleArray, $type->hydrate(json_encode($exampleArray)));
    }
}
