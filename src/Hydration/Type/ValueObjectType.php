<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

class ValueObjectType implements Type, NullableType
{
    use Nullable;

    private $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function hydrate($value): object
    {
        $class = $this->class;

        return new $class($value);
    }

    public function extract($value): string
    {
        return (string) $value;
    }
}
