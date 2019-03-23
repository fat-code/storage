<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\MongoDb\MongoCommand;
use stdClass;

/**
 * The Remove command removes one or more documents from the given collection.
 * @see https://docs.mongodb.com/manual/reference/command/delete/
 */
final class Remove extends MongoCommand
{
    public function __construct(string $collection, array $filter)
    {
        if (empty($filter)) {
            $filter = new stdClass();
        }
        $this->command = [
            'delete' => $collection,
            'deletes' => [
                [
                    'q' => $filter,
                    'limit' => 0,
                ]
            ],
        ];
    }
}
