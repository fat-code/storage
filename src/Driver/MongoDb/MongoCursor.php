<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb;

use FatCode\Storage\Driver\Command;
use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\Cursor;
use FatCode\Storage\Exception\CursorException;
use IteratorIterator;
use MongoDB\Driver\Cursor as MongoDBCursor;

class MongoCursor implements Cursor
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Command
     */
    private $command;

    /**
     * @var MongoDBCursor
     */
    private $baseCursor;

    /**
     * @var object|null
     */
    private $current;

    /**
     * @var callable
     */
    private $onFetch;

    /**
     * @var IteratorIterator
     */
    private $cursorIterator;

    public function __construct(
        Connection $connection,
        Command $command,
        MongoDBCursor $cursor
    ) {
        $this->connection = $connection;
        $this->command = $command;
        $this->baseCursor = $cursor;
        $this->createIterator();
    }

    private function createIterator() : void
    {
        $this->cursorIterator = new IteratorIterator($this->baseCursor);
        $this->cursorIterator->rewind();
        if ($this->cursorIterator->valid()) {
            $this->current = $this->fetch();
        }
    }

    public function getId() : string
    {
        return (string) $this->baseCursor->getId();
    }

    public function getCommand() : Command
    {
        return $this->command;
    }

    public function getConnection() : Connection
    {
        return $this->connection;
    }

    /**
     * Customizes fetch operation with callable. Each time a record is retrieved from database
     * its contents get passed to callable and result of that callable is assigned as value to
     * current item.
     *
     * @param callable $callable
     */
    public function onFetch(callable $callable) : void
    {
        if ($this->onFetch) {
            throw CursorException::forOnFetchAlreadyAssigned($this);
        }

        $this->onFetch = $callable;
        // Because cursor is started before hydration is assigned first record will be
        // already assigned to the current property. To provide seemless operation (like hydration)
        // integration we have to override first element with result of callable.
        if (is_array($this->current)) {
            $this->current = $callable($this->current);
        }
    }

    public function current()
    {
        return $this->current;
    }

    public function next() : void
    {
        $this->cursorIterator->next();
        $this->current = $this->fetch();
    }

    public function key() : int
    {
        return $this->cursorIterator->key();
    }

    public function valid() : bool
    {
        return $this->cursorIterator->valid();
    }

    public function rewind() : void
    {
        $this->cursorIterator->rewind();
    }

    public function toArray() : array
    {
        $result = iterator_to_array($this);
        $this->close();

        return $result;
    }

    public function isClosed() : bool
    {
        return $this->baseCursor === null;
    }

    public function close() : void
    {
        $this->current = null;
        if ($this->baseCursor) {
            $this->baseCursor = null;
            $this->cursorIterator = null;
        }
    }

    private function fetch()
    {
        $fetched = $this->cursorIterator->current();
        if ($this->onFetch && $fetched !== null) {
            $fetched = ($this->onFetch)($fetched);
        }

        return $fetched;
    }

    public function __destruct()
    {
        $this->close();
    }
}
