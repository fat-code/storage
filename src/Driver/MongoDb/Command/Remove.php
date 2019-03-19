<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\MongoDb\MongoCommand;

final class Remove implements MongoCommand
{
    private $command;

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

    public function execute(callable $handler, Connection $connection): void
    {
        $handler($this->command);
    }
}
