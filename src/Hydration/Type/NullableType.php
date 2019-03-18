<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

interface NullableType extends Type
{
    public function nullable(): Type;
    public function isNullable(): bool;
}
