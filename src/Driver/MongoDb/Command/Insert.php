<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\MongoDb\MongoCommand;

final class Insert extends MongoCommand
{
    public function __construct(string $collection, array ...$documents)
    {
        $this->command = [
            'insert' => $collection,
            'documents' => $documents,
        ];
    }
}
