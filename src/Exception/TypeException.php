<?php declare(strict_types=1);

namespace FatCode\Storage\Exception;

use FatCode\Storage\Hydration\Type\Type;

class TypeException extends StorageException
{
    public static function forUnknownType(string $type): self
    {
        return new self("Invalid type specified:  {$type}");
    }

    public static function forValidationFailure(Type $type, string $message = ''): self
    {
        $class = get_class($type);
        return new self("Failed to instantiate type {$class}. {$message}");
    }

    public static function forInvalidTypeRegister(string $type): self
    {

    }
}
