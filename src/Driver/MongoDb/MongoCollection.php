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

class Collection
{
    private $connection;
    private $collection;

    public function __construct(MongoConnection $connection, string $collection)
    {
        $this->connection = $connection;
        $this->collection = $collection;
    }

    public function get(ObjectId $id) : ?array
    {
        $find = new Find($this->collection, ['_id' => $id], new Limit(1));
        $object = $this->connection->execute($find)->current();

        return $object;
    }

    public function find(array $query = [], FindOperation ...$operation) : MongoCursor
    {
        $find = new Find($this->collection, $query, ...$operation);
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

    public function insert(array $document) : bool
    {
        $insert = new Insert($this->collection, $document);
        $cursor = $this->connection->execute($insert);
        $result = $cursor->current();
        $cursor->close();
    }

    public function update(array $document) : bool
    {
        if (!isset($document['_id'])) {
            throw DriverException::forCommandFailure(
                new Update($this->collection),
                'Cannot update document without identifier.'
            );
        }
        $update = new Update(
            $this->collection,
            new Changeset(
                ['_id' => $document['_id']],
                new UpdateDocument($document)
            )
        );
        $cursor = $this->connection->execute($update);
        $result = $cursor->current();
        $cursor->close();
        if ($result['ok'] == 1 && $result['nModified'] > 0) {
            return true;
        }

        return false;
    }

    public function upsert(array $document)
    {

    }

    public function delete(array $document)
    {

    }

    public function findAndDelete(array $query)
    {

    }

    public function aggregate(PipelineOperation ...$operation)
    {

    }

    public function forEach(UpdateOperation ...$changesets)
    {

    }

    public function forId(ObjectId $id, UpdateOperation ...$operation)
    {
        $changeset = new Changeset(['_id' => $id], ...$operation);
        $changeset->multi(false);
        $update = new Update($this->collection, $changeset);
        $this->connection->execute($update);
    }
}
