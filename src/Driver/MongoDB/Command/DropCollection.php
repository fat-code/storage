<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB\Command;

use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\MongoDB\Command;

final class DropCollection implements Command
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
