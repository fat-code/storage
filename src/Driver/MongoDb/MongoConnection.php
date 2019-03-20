<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb;

use FatCode\Storage\Driver\Command;
use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\ConnectionOptions;
use FatCode\Storage\Driver\Cursor as DriverCursor;
use FatCode\Storage\Exception\DriverException;
use MongoDB;
use Throwable;

final class MongoConnection implements Connection
{
    /**
     * @var MongoDB\Driver\Manager
     */
    private $handler;

    /**
     * @var string
     */
    private $host;

    /**
     * @var MongoConnectionOptions
     */
    private $options;

    /**
     * @var MongoCollection[]
     */
    private $collections = [];

    /**
     * MongoConnection constructor.
     * @param array|string $host
     * @param MongoConnectionOptions $options
     */
    public function __construct($host, MongoConnectionOptions $options)
    {
        if (is_string($host)) {
            $host = [$host];
        }

        if (!is_array($host)) {
            throw DriverException::forInvalidArgument('$host', 'expected array or string');
        }

        $this->host = implode(',', $host);
        $this->options = $options;
    }

    public function close(): void
    {
        $this->handler = null;
    }

    public function __get(string $name) : MongoCollection
    {
        if (isset($this->collections[$name])) {
            return $this->collections[$name];
        }
        return $this->collections[$name] = new MongoCollection($this, $name);
    }

    /**
     * @param MongoCommand $command
     * @return MongoCursor
     */
    public function execute(Command $command): DriverCursor
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        try {
            /** @var MongoDB\Driver\Cursor $nativeCursor */
            $nativeCursor = null;

            $command->execute(
                function (array $command) use (&$nativeCursor) {
                    $nativeCursor = $this->handler->executeCommand(
                        $this->options->getDatabase(),
                        new MongoDB\Driver\Command($command)
                    );
                },
                $this
            );
            $nativeCursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);

            return new MongoCursor($this, $command, $nativeCursor);
        } catch (Throwable $throwable) {
            throw DriverException::forCommandFailure($command, $throwable->getMessage());
        }
    }

    public function getOptions(): ConnectionOptions
    {
        return $this->options;
    }

    public function connect(): void
    {
        $this->handler = new MongoDB\Driver\Manager(
            'mongodb://' . $this->host . '/' . $this->options->getDatabase(),
            $this->options->getURIOptions(),
            $this->options->getDriverOptions()
        );
    }

    public function isConnected(): bool
    {
        return $this->handler !== null;
    }

    public function getBaseConnection(): MongoDB\Driver\Manager
    {
        return $this->handler;
    }
}
