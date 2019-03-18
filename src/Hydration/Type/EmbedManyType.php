<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

class EmbedManyType implements Type
{
    private $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function hydrate($value)
    {
        return $value;
    }

    public function extract($value)
    {
        return $value;
    }
}
