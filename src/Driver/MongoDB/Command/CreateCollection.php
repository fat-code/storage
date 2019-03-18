<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB\Command;

use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\MongoDB\Command;

final class CreateCollection implements Command
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
