<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\MongoDb\MongoCommand;

/**
 * Lists collections existing in the database.
 */
final class ListCollections extends MongoCommand
{
    public function __construct(array $filter = null)
    {
        $this->command = [
            'listCollections' => 1,
            'nameOnly' => true,
        ];

        if (null !== $filter) {
            $this->command['filter'] = $filter;
        }
    }
}
