<?php declare(strict_types=1);

namespace FatCode\Storage\Driver;

interface Connection
{
    public function close(): void;
    public function connect(): void;
    public function isConnected(): bool;
    public function execute(Command $command): Cursor;
    public function getOptions(): ConnectionOptions;
}
