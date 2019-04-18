<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

interface FindOperation
{
    public function apply() : array;
}
