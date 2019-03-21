<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\MongoDb\Collation;
use FatCode\Storage\Driver\MongoDb\MongoCommand;

/**
 * Creates new collection in database, if collection exists it will be
 * overridden!
 */
final class CreateCollection extends MongoCommand
{
    public function __construct(string $name, Collation $collation = null)
    {
        $this->command = [
            'create' => $name,
        ];

        if ($collation) {
            $this->command += $collation->apply();
        }
    }
}
