<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

class StringType implements Type, NullableType
{
    use Nullable;

    public function hydrate($value): string
    {
        return (string) $value;
    }

    public function extract($value): string
    {
        return (string) $value;
    }
}
