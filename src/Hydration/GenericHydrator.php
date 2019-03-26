<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

use Closure;
use FatCode\Storage\Exception\HydrationException;
use FatCode\Storage\Hydration\Type\CompositeType;
use FatCode\Storage\Hydration\Type\NullableType;
use FatCode\Storage\Hydration\Type\Type;

trait GenericHydrator
{
    /**
     * @var IdentityMap
     */
    protected $identityMap;

    public function setIdentityMap(IdentityMap $identityMap) : void
    {
        $this->identityMap = $identityMap;
    }

    private function hydrateObject(Schema $schema, array $input, object $object) : object
    {
        $id = null;
        if ($schema->definesId()) {
            $idField = $schema->getNamingStrategy()->map($schema->getIdName());
            if (isset($input[$idField])) {
                $id = $input[$idField];
            }
        }

        if ($this->identityMap && $id && $this->identityMap->has($id)) {
            return $this->identityMap->get($id);
        }

        $namingStrategy = $schema->getNamingStrategy();

        /** @var Type $type */
        foreach ($schema as $property => $type) {
            if ($type instanceof CompositeType) {
                $values = [];
                foreach ($type->getKeys($property) as $key) {
                    $mappedKey = $namingStrategy->map($key);
                    $values[] = $input[$mappedKey] ?? null;
                }
                if (in_array(null, $values, true)) {
                    if (!$type->isNullable()) {
                        throw HydrationException::forNullHydration($schema, $property);
                    }
                    $this->writeProperty($object, $property, null);
                    continue;
                }
                $this->writeProperty($object, $property, $type->hydrate($values));
                continue;
            }

            $value = $input[$namingStrategy->map($property)] ?? null;
            if ($value === null) {
                if (!$type instanceof NullableType || !$type->isNullable()) {
                    throw HydrationException::forNullHydration($schema, $property);
                }
                continue;
            }

            $this->writeProperty($object, $property, $type->hydrate($value));
        }

        if ($this->identityMap && $id) {
            $this->identityMap->attach($object, $id);
        }

        return $object;
    }

    protected function writeProperty(object $object, string $property, $value) : void
    {
        static $writer;
        if ($writer === null) {
            $writer = function ($name, $value) {
                if (!property_exists($this, $name)) {
                    throw new \Exception("Pole nie istnieje");
                }
                $this->$name = $value;
            };
        }

        $set = Closure::bind($writer, $object, $object);
        $set($property, $value);
    }
}
