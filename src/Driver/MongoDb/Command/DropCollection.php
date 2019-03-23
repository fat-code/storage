<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\MongoDb\MongoCommand;

/**
 * The drop command removes an entire collection from a database.
 * @see https://docs.mongodb.com/manual/reference/command/drop/
 */
final class DropCollection extends MongoCommand
{
    public function __construct(string $collection)
    {
        $this->command = [
            'drop' => $collection,
        ];
    }
}
