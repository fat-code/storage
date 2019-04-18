<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\MongoDb\Command\Operation\UpdateOperation;
use stdClass;

use function array_merge_recursive;

class Changeset
{
    private $query;
    private $upsert = false;
    private $multi = false;
    private $operations;

    public function __construct(array $query, UpdateOperation ...$operations)
    {
        // Fix for empty search term.
        if (empty($query)) {
            $query = new stdClass();
        }
        $this->query = $query;
        $this->operations = $operations;
    }

    public function multi(bool $multi) : void
    {
        $this->multi = $multi;
    }

    public function upsert(bool $upsert) : void
    {
        $this->upsert = $upsert;
    }

    public function isEmpty(): bool
    {
        return empty($this->operations);
    }

    public function generate(): array
    {
        $operations = [];
        foreach ($this->operations as $operation) {
            $operations[] = $operation->apply();
        }

        return [
            'upsert' => $this->upsert,
            'q' => $this->query,
            'u' => array_merge_recursive(...$operations),
            'multi' => $this->multi
        ];
    }
}
