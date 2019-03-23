<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

interface SchemaLoader
{
    public function load(string $class): ?Schema;
}
