<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB\Command;

use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\MongoDB\Command;

final class Remove implements Command
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
