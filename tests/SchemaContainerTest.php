<?php declare(strict_types=1);

namespace FatCode\Tests\Storage;

use FatCode\Storage\Exception\SchemaException;
use FatCode\Storage\Schema;
use FatCode\Storage\SchemaContainer;
use FatCode\Storage\SchemaLoader;
use FatCode\Tests\Storage\Fixtures\User;
use FatCode\Tests\Storage\Fixtures\UserSchema;
use PHPUnit\Framework\TestCase;

final class SchemaContainerTest extends TestCase
{
    public function testRegisterSchema() : void
    {
        $schema = new UserSchema();
        $schemaContainer = new SchemaContainer();
        $schemaContainer->register($schema);

        self::assertSame($schema, $schemaContainer->get($schema->getTargetClass()));
    }

    public function testRegisterLoader() : void
    {
        $schemaContainer = new SchemaContainer();
        self::assertFalse($schemaContainer->has(User::class));

        $loader = new class implements SchemaLoader {
            public function load(string $class): ?Schema
            {
                if ($class === User::class) {
                    return new UserSchema();
                }
            }
        };

        self::assertFalse($schemaContainer->hasLoader($loader));

        $schemaContainer->addLoader($loader);
        self::assertTrue($schemaContainer->hasLoader($loader));
        self::assertTrue($schemaContainer->has(User::class));
        self::assertInstanceOf(UserSchema::class, $schemaContainer->get(User::class));
    }

    public function testFailGetOnUndefinedSchema() : void
    {
        $this->expectException(SchemaException::class);
        $schemaContainer = new SchemaContainer();
        $schemaContainer->get('Something');
    }
}
