<?php declare(strict_types=1);

namespace FatCode\Tests\Storage;

use FatCode\Storage\Exception\SchemaException;
use FatCode\Storage\Schema;
use FatCode\Storage\ObjectHydrator;
use FatCode\Storage\SchemaLoader;
use FatCode\Tests\Storage\Fixtures\User;
use FatCode\Tests\Storage\Fixtures\UserSchema;
use PHPUnit\Framework\TestCase;

final class ObjectHydratorTest extends TestCase
{
    public function testRegisterSchema() : void
    {
        $schema = new UserSchema();
        $objectHydrator = new ObjectHydrator();
        $objectHydrator->addSchema($schema);

        self::assertSame($schema, $objectHydrator->getSchema($schema->getTargetClass()));
    }

    public function testRegisterLoader() : void
    {
        $objectHydrator = new ObjectHydrator();
        self::assertFalse($objectHydrator->hasSchema(User::class));

        $loader = new class implements SchemaLoader {
            public function load(string $class): ?Schema
            {
                if ($class === User::class) {
                    return new UserSchema();
                }

                return null;
            }
        };
        $objectHydrator->addSchemaLoader($loader);
        self::assertTrue($objectHydrator->hasSchema(User::class));
        self::assertInstanceOf(UserSchema::class, $objectHydrator->getSchema(User::class));
    }

    public function testFailGetOnUndefinedSchema() : void
    {
        $this->expectException(SchemaException::class);
        $objectHydrator = new ObjectHydrator();
        $objectHydrator->getSchema('Something');
    }
}
