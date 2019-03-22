<?php declare(strict_types=1);

namespace FatCode\Storage\Exception;

use RuntimeException;

class HydrationException extends RuntimeException
{
    public static function forUndeclaredClass(string $name): self
    {
        return new self("Class with name {$name} is not declared anywhere. Check your composer config.");
    }

    public static function forInstantiationError(string $name, string $message = ''): self
    {
        return new self("Could not instantiate {$name} class. {$message}");
    }

    public static function forInvalidPropertySetting(string $property, string $message): self
    {
        return new self("Could not hydrate/extract {$property} property. {$message}");
    }

    public static function forUnexpectedInstance(string $expected, string $passed): self
    {
        return new self("Could not extract, expected instance of {$expected}, {$passed} passed instead.");
    }

    public static function forNullHydration(): self
    {
        return new self("Could not hydrate instance of null. Generic hydrator requires an instance to be passed.");
    }

    public static function forHydrationError(object $object, string $message): self
    {
        $class = get_class($object);
        return new self("Could not hydrate instance of {$class}, are you sure that name mapping is set correctly? Failure message: {$message}");
    }

    public static function forUnallowedNullable() : self
    {
        return new self("Cannot extract/hydrate null value, field is not nullable. Please set value or make field nullable.");
    }
}
