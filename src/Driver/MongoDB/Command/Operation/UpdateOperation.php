<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB\Command\Operation;

interface UpdateOperation
{
    public function apply(): array;
}
