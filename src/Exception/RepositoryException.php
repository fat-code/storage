<?php declare(strict_types=1);

namespace FatCode\Storage\Exception;

use FatCode\Storage\Hydration\Schema;
use RuntimeException;

class RepositoryException extends RuntimeException
{
    public static function forSchemaWithoutSourceDefinition(Schema $schema) : self
    {
        $schemaClass = get_class($schema);
        return new self("Repository expects schema to define storage source, {$schemaClass} provides no source." .
            'Please implement `getSource` method and return valid source.');
    }
}
