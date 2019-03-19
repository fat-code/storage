<?php declare(strict_types=1);

namespace FatCode\Storage\Exception;

use FatCode\Storage\Driver\Command;
use FatCode\Storage\Driver\MongoDb\Command\Operation\UpdateOperation;
use function get_class;

class DriverException extends StorageException
{
    public static function forCommandFailure(Command $command, string $message) : self
    {
        $class = get_class($command);

        return new self("Failed to execute command {$class} - {$message}");
    }

    public static function forOperationFailure(UpdateOperation $operation, string $message) : self
    {
        $class = get_class($operation);

        return new self("Operator {$class} has failed - {$message}");
    }

    public static function forInvalidArgument(string $name, string $message) : self
    {
        return new self("Invalid {$name} argument passed - {$message}");
    }
}
