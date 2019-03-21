<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\MongoDb\MongoCommand;

final class DropCollection extends MongoCommand
{
    private $command;

    public function __construct(string $collection)
    {
        $this->command = [
            'drop' => $collection,
        ];
    }
}
