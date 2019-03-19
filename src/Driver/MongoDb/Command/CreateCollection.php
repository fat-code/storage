<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\MongoDb\MongoCommand;

final class CreateCollection implements MongoCommand
{
    private $command;

    public function __construct(string $name, array $collation = null)
    {
        $this->command = [
            'create' => $name,
        ];

        if ($collation) {
            $this->command['collation'] = $collation;
        }
    }

    public function execute(callable $handler, Connection $connection): void
    {
        $handler($this->command);
    }
}
