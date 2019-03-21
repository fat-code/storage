<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb;

use FatCode\Storage\Driver\Command;
use FatCode\Storage\Driver\Connection;

abstract class MongoCommand implements Command
{
    public function execute(callable $handler, Connection $connection) : void
    {
        $handler($this->command);
    }
}
