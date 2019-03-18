<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

class IdType implements NamedType
{
    public function hydrate($value)
    {
        return $value;
    }

    public function extract($value)
    {
        return $value;
    }

    public function getLocalName(): string
    {
        return 'id';
    }

    public function getExternalName(): string
    {
        return '_id';
    }
}
