<?php declare(strict_types=1);

namespace FatCode\Storage\Driver;

interface MemorySavingCursor extends HydratingCursor
{
    public function saveMemory(bool $save = true): void;
}
