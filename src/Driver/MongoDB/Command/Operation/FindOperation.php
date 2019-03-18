<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB\Command\Operation;

interface FindOperation
{
    public function apply(): array;
}
