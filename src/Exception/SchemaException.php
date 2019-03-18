<?php declare(strict_types=1);

namespace Stilus\Library\Exception;

use Stilus\Exception\RuntimeException;

class SchemaException extends \RuntimeException implements RuntimeException
{
    public static function forMissingIdentifier(string $class): self
    {
        return new self("Schema definition for {$class} contains no identifier. Please add IdProperty to the schema.");
    }

    public static function forMissingProperty(string $class, string $property): self
    {
        return new self("Schema definition for {$class} contains no {$property} property.");
    }
}
