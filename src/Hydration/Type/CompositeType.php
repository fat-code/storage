<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

interface CompositeType extends Type
{
    public function getKeys(string $prefix) : array;
}
