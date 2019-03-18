<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB;

use MongoDB;
use FatCode\Storage\Driver\Command;
use FatCode\Storage\Driver\ConnectionOptions;
use FatCode\Storage\Driver\Cursor as DriverCursor;
use FatCode\Storage\Exception\DriverException;
use Throwable;

final class Connection implements \FatCode\Storage\Driver\Connection
{
    /** @var MongoDB\Driver\Manager */
    private $handler;

    /** @var string */
    private $host;

    /** @var \FatCode\Storage\Driver\MongoDB\ConnectionOptions */
    private $options;

    public function __construct(string $host, ConnectionOptions $options = null)
    {
        $this->host = $host;
        $this->options = $options;
    }

    public function close(): void
    {
        $this->handler = null;
    }

    /**
     * @param Command $command
     * @return Cursor
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

            return new Cursor($this, $command, $nativeCursor);
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
