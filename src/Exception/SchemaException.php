<?php declare(strict_types=1);

namespace FatCode\Storage\Exception;

class SchemaException extends StorageException
{
    public static function forMissingIdentifier(string $class): self
    {
        return new self("Schema definition for {$class} contains no identifier. Please add IdProperty to the schema.");
    }

    public static function forMissingProperty(string $class, string $property): self
    {
        return new self("Schema definition for {$class} contains no {$property} property.");
    }

    public static function forUndefinedSchema(string $class) : self
    {
        return new self("Schema definition for {$class} could not be loaded.");
    }
}
