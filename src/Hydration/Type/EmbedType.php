<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

use FatCode\Storage\Exception\HydrationException;
use FatCode\Storage\Hydration\GenericExtractor;
use FatCode\Storage\Hydration\GenericHydrator;
use FatCode\Storage\Hydration\Instantiator;
use FatCode\Storage\Hydration\Schema;

class EmbedType implements Type, NullableType
{
    use Nullable, GenericHydrator, GenericExtractor;

    private $schema;

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function hydrate($value) : object
    {
        $object = Instantiator::instantiate($this->schema->getTargetClass());
        return $this->hydrateObject($this->schema, $value, $object);
    }

    public function extract($value) : ?array
    {
        if ($value === null) {
            if ($this->nullable) {
                return null;
            }
            throw HydrationException::forUnallowedNullable();
        }

        return $this->extractObject($this->schema, $value);
    }
}
