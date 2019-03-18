<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB\Command;

use FatCode\Storage\Driver\MongoDB\Command\Operation\UpdateDocument;
use FatCode\Storage\Driver\MongoDB\Command\Operation\UpdateOperation;
use FatCode\Storage\Exception\DriverException;

use function array_merge_recursive;

class Changeset
{
    private $query;
    private $upsert = false;
    private $operations;

    public function __construct(array $query, UpdateOperation ...$operations)
    {
        $this->query = $query;
        $this->operations = $operations;
    }

    public function upsert(bool $upsert): void
    {
        $this->upsert = $upsert;
    }

    /**
     * @param array $document
     * @param bool $upsert
     * @return Changeset
     */
    public static function forDocument(array $document, bool $upsert = false): self
    {
        $operation = new UpdateDocument($document);

        if (!isset($document['_id'])) {
            throw DriverException::forOperationFailure($operation, 'Cannot update document without identifier.');
        }
        $changeset = new self(['_id' => $document['_id'] ?? $document['id']], $operation);
        $changeset->upsert($upsert);

        return $changeset;
    }

    public static function forId($id, UpdateOperation ...$operations): self
    {
        return new self(['_id' => $id], ...$operations);
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
        ];
    }
}
