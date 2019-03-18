<?php declare(strict_types=1);

namespace FatCode\Storage\Driver;

use Iterator;

interface Cursor extends Iterator
{
    public function getConnection(): Connection;
    public function close(): void;
    public function isClosed(): bool;
}
