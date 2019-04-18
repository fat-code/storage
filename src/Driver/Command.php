<?php declare(strict_types=1);

namespace FatCode\Storage\Driver;

interface Command
{
    public function execute(callable $handler, Connection $connection) : void;
}
