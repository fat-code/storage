<?php declare(strict_types=1);

namespace FatCode\Storage\Exception;

use RuntimeException;

class IdentityMapException extends RuntimeException
{
    public static function forMissingObject($id) : self
    {
        return new self("Could not retrieve object with {$id}");
    }
}
