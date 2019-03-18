<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Property;

use PHPUnit\Framework\TestCase;
use FatCode\Storage\Hydration\Schema\Schema;
use FatCode\Storage\Hydration\Type;
use FatCode\Storage\Hydration\Type\IdType;
use FatCode\Storage\Hydration\Type\IntegerType;
use Stilus\Tests\Fixtures\UserFixture;

final class SchemaTest extends TestCase
{
    public function testAddProperty(): void
    {
        $schema = new Schema(UserFixture::class, [
            'test' => Type::string(),
        ]);

        self::assertTrue($schema->hasProperty('test'));
    }

    public function testHasId(): void
    {
        $schema = new Schema(UserFixture::class, []);
        self::assertFalse($schema->hasId());
        $schema->addProperty('name', new IntegerType());
        self::assertFalse($schema->hasId());
        $schema->addProperty('id', new IdType());
        self::assertTrue($schema->hasId());
    }
}
