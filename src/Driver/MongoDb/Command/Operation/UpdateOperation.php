<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

interface UpdateOperation
{
    public function apply(): array;
}
