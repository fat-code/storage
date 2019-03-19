<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\MongoDb\MongoCommand;

final class Update implements MongoCommand
{
    private $command;

    public function __construct(string $collection, Changeset ...$changesets)
    {
        $updates = [];
        foreach ($changesets as $changeset) {
            $updates[] = $changeset->generate();
        }
        $this->command = [
            'update' => $collection,
            'updates' => $updates,
        ];
    }

    public function execute(callable $handler, Connection $connection): void
    {
        $handler($this->command);
    }
}
