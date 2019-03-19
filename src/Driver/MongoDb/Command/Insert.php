<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\MongoDb\MongoCommand;

final class Insert implements MongoCommand
{
    private $command;

    public function __construct(string $collection, array ...$documents)
    {
        $this->command = [
            'insert' => $collection,
            'documents' => $documents,
        ];
    }

    public function execute(callable $handler, Connection $connection): void
    {
        $handler($this->command);
    }
}
