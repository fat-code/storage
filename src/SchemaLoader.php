<?php declare(strict_types=1);

namespace FatCode\Storage;

interface SchemaLoader
{
    public function load(string $class): ?Schema;
}
