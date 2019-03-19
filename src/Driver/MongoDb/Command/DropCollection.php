<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\MongoDb\MongoCommand;

final class DropCollection implements MongoCommand
{
    private $command;

    public function __construct(string $collection)
    {
        $this->command = [
            'drop' => $collection,
        ];
    }

    public function execute(callable $handler, Connection $connection): void
    {
        $handler($this->command);
    }
}
