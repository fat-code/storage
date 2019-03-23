<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\MongoDb\MongoCommand;

/**
 * The RemoveById command removes one or more documents from the given collection by theirs id.
 * @see https://docs.mongodb.com/manual/reference/command/delete/
 */
final class RemoveById extends MongoCommand
{
    public function __construct(string $collection, ...$ids)
    {
        $deletes = [];
        foreach ($ids as $id) {
            $deletes[] = [
                'q' => [
                    '_id' => $id,
                ],
                'limit' => 1,
            ];
        }

        $this->command = [
            'delete' => $collection,
            'deletes' => $deletes,
        ];
    }
}
