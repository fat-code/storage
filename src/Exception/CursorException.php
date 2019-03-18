<?php declare(strict_types=1);

namespace FatCode\Storage\Exception;

use FatCode\Storage\Driver\Cursor;
use function get_class;

class CursorException extends DriverException
{
    public static function forHydratorAlreadyAssigned(Cursor $cursor): self
    {
        $class = get_class($cursor);

        throw new self("Hydrator for cursor {$class} has been already assigned.");
    }
}
