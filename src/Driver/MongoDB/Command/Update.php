<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB\Command;

use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\MongoDB\Command;

final class Update implements Command
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

    public static function forDocument(string $collection, array $document, bool $upsert = false): self
    {
        $command = new self($collection);
        $command->command['updates'] = [Changeset::forDocument($document, $upsert)->generate()];

        return $command;
    }

    public static function forDocuments(string $collection, array ...$documents): self
    {
        $command = new self($collection);
        foreach ($documents as $document) {
            $command->command['updates'][] = Changeset::forDocument($document)->generate();
        }

        return $command;
    }

    public function execute(callable $handler, Connection $connection): void
    {
        $handler($this->command);
    }
}
