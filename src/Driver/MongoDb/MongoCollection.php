<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb;

use FatCode\Storage\Driver\MongoDb\Command\Aggregate;
use FatCode\Storage\Driver\MongoDb\Command\Changeset;
use FatCode\Storage\Driver\MongoDb\Command\Find;
use FatCode\Storage\Driver\MongoDb\Command\Insert;
use FatCode\Storage\Driver\MongoDb\Command\Operation\FindOperation;
use FatCode\Storage\Driver\MongoDb\Command\Operation\Limit;
use FatCode\Storage\Driver\MongoDb\Command\Operation\PipelineOperation;
use FatCode\Storage\Driver\MongoDb\Command\Operation\UpdateDocument;
use FatCode\Storage\Driver\MongoDb\Command\Operation\UpdateOperation;
use FatCode\Storage\Driver\MongoDb\Command\Remove;
use FatCode\Storage\Driver\MongoDb\Command\RemoveById;
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
     * @param ObjectId|mixed $id
     * @return array|null
     */
    public function get($id) : ?array
    {
        $find = new Find($this->collection, ['_id' => $id], new Limit(1));
        $object = $this->connection->execute($find)->current();

        return $object;
    }

    /**
     * Finds documents matching the filter.
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

    /**
     * Finds and retrieves first document matching the filter.
     *
     * @param array $query
     * @param FindOperation ...$operation
     * @return array|null
     */
    public function findOne(array $query = [], FindOperation ...$operation) : ?array
    {
        $operation[] = new Limit(1);
        $find = new Find($this->collection, $query, ...$operation);
        $cursor = $this->connection->execute($find);
        $object = $cursor->current();

        return $object;
    }

    /**
     * Persists one or more documents in the collection.
     *
     * @param array ...$document
     * @return bool
     */
    public function insert(array ...$document) : bool
    {
        $insert = new Insert($this->collection, ...$document);
        $cursor = $this->connection->execute($insert);
        $result = $cursor->current();
        $cursor->close();

        return $result['ok'] == 1 && $result['n'] == count($document);
    }

    /**
     * Updates one or more documents in the collection.
     *
     * @param array ...$document
     * @return bool
     */
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
            $changeSets[] = new Changeset(
                ['_id' => $item['_id']],
                new UpdateDocument($item)
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

    /**
     * Inserts or Updates one or more documents in the collection.
     *
     * @param array ...$document
     * @return bool
     */
    public function upsert(array ...$document) : bool
    {
        $changeSets = [];
        foreach ($document as $item) {
            if (!isset($item['_id'])) {
                throw DriverException::forCommandFailure(
                    new Update($this->collection),
                    'Cannot upsert document without identifier.'
                );
            }
            $change = new Changeset(
                ['_id' => $item['_id']],
                new UpdateDocument($item)
            );
            $change->upsert(true);
            $changeSets[] = $change;
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

    /**
     * Removes document from the collection, returns `true` on success otherwise `false`.
     * @param ObjectId|mixed ...$id
     * @return bool
     */
    public function delete(...$id) : bool
    {
        $delete = new RemoveById($this->collection, ...$id);
        $cursor = $this->connection->execute($delete);
        $result = $cursor->current();
        $cursor->close();

        return $result['n'] == 1 && $result['ok'] == 1;
    }

    /**
     * Modifies all documents matching filter with passed operations.
     *
     * @param array $filter
     * @param UpdateOperation ...$operation
     * @return int the amount of changed documents
     */
    public function findAndModify(array $filter, UpdateOperation ...$operation) : int
    {
        $changeset = new Changeset($filter, ...$operation);
        $changeset->multi(false);
        $update = new Update($this->collection, $changeset);
        $cursor = $this->connection->execute($update);
        $result = $cursor->current();
        $cursor->close();

        return $result['nModified'];
    }

    /**
     * Removes all documents matching the filter
     * @param array $filter
     * @return int
     */
    public function findAndDelete(array $filter) : int
    {
        $command = new Remove($this->collection, $filter);
        $cursor = $this->connection->execute($command);
        $result = $cursor->current();
        $cursor->close();

        return $result['n'];
    }

    /**
     * Performs aggregation and returns cursor.
     *
     * @param PipelineOperation ...$operation
     * @return MongoCursor
     */
    public function aggregate(PipelineOperation ...$operation) : MongoCursor
    {
        $command = new Aggregate($this->collection, ...$operation);
        return $this->connection->execute($command);
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
     * @param ObjectId|mixed $id
     * @param UpdateOperation ...$operation
     * @return bool
     */
    public function forId($id, UpdateOperation ...$operation) : bool
    {
        return $this->findAndModify(['_id' => $id], ...$operation) === 1;
    }
}
