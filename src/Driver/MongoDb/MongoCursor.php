<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb;

use FatCode\Storage\Driver\HydratingCursor;
use IteratorIterator;
use MongoDB\Driver\Cursor as MongoDBCursor;
use FatCode\Storage\Driver\Command;
use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Exception\CursorException;
use FatCode\Storage\Hydration\Hydrator;

class MongoCursor implements HydratingCursor
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
     * @var Hydrator
     */
    private $hydrator;

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

    private function createIterator(): void
    {
        $this->cursorIterator = new IteratorIterator($this->baseCursor);
        $this->cursorIterator->rewind();
        if ($this->cursorIterator->valid()) {
            $this->current = $this->fetch();
        }
    }

    public function getId(): string
    {
        return (string) $this->baseCursor->getId();
    }

    public function getCommand(): Command
    {
        return $this->command;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function hydrateWith(Hydrator $hydrator): void
    {
        if ($this->hydrator) {
            throw CursorException::forHydratorAlreadyAssigned($this);
        }

        $this->hydrator = $hydrator;
        // Because cursor is started before hydration is assigned first record will be
        // already assigned to the current property. To provide seemless hydrator
        // integration we have to hydrate the element once hydrator is passed
        if (is_array($this->current)) {
            $this->current = $hydrator->hydrate($this->current);
        }
    }

    public function current()
    {
        return $this->current;
    }

    public function next(): void
    {
        $this->cursorIterator->next();
        $this->current = $this->fetch();
    }

    public function key(): int
    {
        return $this->cursorIterator->key();
    }

    public function valid(): bool
    {
        return $this->cursorIterator->valid();
    }

    public function rewind(): void
    {
        $this->cursorIterator->rewind();
    }

    public function toArray(): array
    {
        $result = iterator_to_array($this);
        $this->close();

        return $result;
    }

    public function isClosed(): bool
    {
        return $this->baseCursor === null;
    }

    public function close(): void
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
        if ($this->hydrator && $fetched !== null) {
            $fetched = $this->hydrator->hydrate($fetched);
        }

        return $fetched;
    }

    public function __destruct()
    {
        $this->close();
    }
}
