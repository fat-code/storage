<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\MongoDb\MongoCommand;

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
