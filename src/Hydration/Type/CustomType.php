<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

use FatCode\Storage\Hydration\Hydrator;

class CustomType implements Type, NullableType
{
    use Nullable;

    private $hydrator;

    public function __construct(Hydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    public function hydrate($value): object
    {
        return $this->hydrator->hydrate($value);
    }

    public function extract($object): array
    {
        return $this->hydrator->extract($object);
    }
}
