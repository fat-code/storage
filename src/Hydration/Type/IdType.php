<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

class IdType implements Type
{
    public function hydrate($value)
    {
        return $value;
    }

    public function extract($value)
    {
        return $value;
    }
}
