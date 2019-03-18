<?php declare(strict_types=1);

namespace FatCode\Storage;

use FatCode\Storage\Exception\SchemaException;

use function get_class;
use function in_array;
use function is_object;

final class SchemaManager
{
    /** @var Schema[] */
    private static $registry = [];

    /** @var SchemaLoader[] */
    private static $loaders = [];

    public static function addLoader(SchemaLoader $loader): void
    {
        self::$loaders[] = $loader;
    }

    public static function hasLoader(SchemaLoader $loader): bool
    {
        return in_array($loader, self::$loaders);
    }

    public static function register(Schema $schema): void
    {
        self::$registry[$schema->getClass()] = $schema;
    }

    public static function define(string $class, array $properties): Schema
    {
        return self::$registry[$class] = new Schema($class, $properties);
    }

    public static function defined(string $class): bool
    {
        return isset(self::$registry[$class]) || self::load($class);
    }

    public static function get($class): Schema
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        if (!self::defined($class)) {
            if (!self::load($class)) {
                throw SchemaException::forUndefinedSchema($class);
            }
        }

        return self::$registry[$class];
    }

    private static function load(string $class): bool
    {
        foreach (self::$loaders as $loader) {
            $schema = $loader->load($class);
            if ($schema !== null) {
                self::$registry[$class] = $schema;
                return true;
            }
        }

        return false;
    }
}
