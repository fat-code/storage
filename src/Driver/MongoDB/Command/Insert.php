<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB\Command;

use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\MongoDB\Command;

final class Insert implements Command
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
