<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

class IntegerType implements Type, NullableType
{
    use Nullable;
    
    public function hydrate($value): int
    {
        return (int) $value;
    }

    public function extract($value): int
    {
        return (int) $value;
    }
}
