<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb;

use FatCode\Storage\Driver\MongoDb\Command\Changeset;
use FatCode\Storage\Driver\MongoDb\Command\Find;
use FatCode\Storage\Driver\MongoDb\Command\Insert;
use FatCode\Storage\Driver\MongoDb\Command\Operation\FindOperation;
use FatCode\Storage\Driver\MongoDb\Command\Operation\Limit;
use FatCode\Storage\Driver\MongoDb\Command\Operation\PipelineOperation;
use FatCode\Storage\Driver\MongoDb\Command\Operation\UpdateDocument;
use FatCode\Storage\Driver\MongoDb\Command\Operation\UpdateOperation;
use FatCode\Storage\Driver\MongoDb\Command\Update;
use FatCode\Storage\Exception\DriverException;
use MongoDB\BSON\ObjectId;

class MongoCollection
{
    private $connection;
    private $collection;

    public function __construct(MongoConnection $connection, string $collection)
    {
        $this->connection = $connection;
        $this->collection = $collection;
    }

    /**
     * Retrieves and returns document from the collection by its id,
     * null is returned if document was not found.
     *
     * @param ObjectId $id
     * @return array|null
     */
    public function get(ObjectId $id) : ?array
    {
        $find = new Find($this->collection, ['_id' => $id], new Limit(1));
        $object = $this->connection->execute($find)->current();

        return $object;
    }

    /**
     * Finds documents matching the filter
     *
     * @param array $filter
     * @param FindOperation ...$operation
     * @return MongoCursor
     */
    public function find(array $filter = [], FindOperation ...$operation) : MongoCursor
    {
        $find = new Find($this->collection, $filter, ...$operation);
        return $this->connection->execute($find);
    }

    public function findOne(array $query = [], FindOperation ...$operation) : ?array
    {
        $operation[] = new Limit(1);
        $find = new Find($this->collection, $query, ...$operation);
        $cursor = $this->connection->execute($find);
        $object = $cursor->current();

        return $object;
    }

    public function insert(array ...$document) : bool
    {
        $insert = new Insert($this->collection, ...$document);
        $cursor = $this->connection->execute($insert);
        $result = $cursor->current();
        $cursor->close();

        return $result['ok'] == 1 && $result['n'] == count($document);
    }

    public function update(array ...$document) : bool
    {
        $changeSets = [];
        foreach ($document as $item) {
            if (!isset($item['_id'])) {
                throw DriverException::forCommandFailure(
                    new Update($this->collection),
                    'Cannot update document without identifier.'
                );
            }
            $changeSets = new Changeset(
                ['_id' => $document['_id']],
                new UpdateDocument($document)
            );
        }

        $update = new Update(
            $this->collection,
            ...$changeSets
        );
        $cursor = $this->connection->execute($update);
        $result = $cursor->current();
        $cursor->close();

        return $result['ok'] == 1 && $result['n'] === count($document);
    }

    public function upsert(array $document)
    {
    }

    public function delete(array ...$document)
    {
    }

    public function findAndDelete(array $query)
    {
    }

    public function aggregate(PipelineOperation ...$operation)
    {
    }

    /**
     * Applies update operation(s) on each document and returns number
     * of modified documents in the database
     *
     * @param UpdateOperation ...$operation
     * @return int
     */
    public function forEach(UpdateOperation ...$operation) : int
    {
        $changeset = new Changeset([], ...$operation);
        $changeset->multi(true);
        $update = new Update($this->collection, $changeset);
        $cursor = $this->connection->execute($update);
        $result = $cursor->current();
        $cursor->close();

        return $result['nModified'];
    }

    /**
     * Applies update operation(s) on document with given id and returns
     * boolean flag, true if any changes were made, otherwise false.
     *
     * @param ObjectId $id
     * @param UpdateOperation ...$operation
     * @return bool
     */
    public function forId(ObjectId $id, UpdateOperation ...$operation) : bool
    {
        $changeset = new Changeset(['_id' => $id], ...$operation);
        $changeset->multi(false);
        $update = new Update($this->collection, $changeset);
        $cursor = $this->connection->execute($update);
        $result = $cursor->current();
        $cursor->close();

        return $result['ok'] == 1 && $result['n'] == 1;
    }
}
