<?php declare(strict_types=1);

namespace FatCode\Storage\Exception;

use MongoDB\BSON\ObjectId;
use RuntimeException;

class IdentityMapException extends RuntimeException
{
    public static function forMissingObject(ObjectId $id): self
    {
        return new self("Could not retrieve object with {$id}");
    }
}
