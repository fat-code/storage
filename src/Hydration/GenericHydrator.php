<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

use Closure;
use FatCode\Storage\Exception\HydrationException;
use FatCode\Storage\Hydration\Type\CompositeType;
use FatCode\Storage\Hydration\Type\EmbedManyType;
use FatCode\Storage\Hydration\Type\EmbedType;
use FatCode\Storage\Hydration\Type\NullableType;
use Throwable;

trait GenericHydrator
{
    protected $identityMap;
    protected $schemaManager;

    protected function setIdentityMap(IdentityMap $identityMap)
    {
        $this->identityMap = $identityMap;
    }

    public function hydrate(array $input, object $object = null): object
    {
        if ($object === null) {
            throw HydrationException::forNullHydration();
        }

        return $this->hydrateObject($input, $object);
    }

    protected function hydrateObject(array $input, object $object) : object
    {
        $schema = SchemaManager::get($object);
        $id = null;
        if ($schema->hasId()) {
            $idField = $schema->getNamingStrategy()->map($schema->getId());
            if (isset($input[$idField])) {
                $id = $input[$idField];
            }
        }

        if ($this->identityMap && $id && $this->identityMap->has($id)) {
            return $this->identityMap->get($id);
        }

        $namingStrategy = $schema->getNamingStrategy();

        try {
            /** @var Type $type */
            foreach ($schema as $property => $type) {
                if ($type instanceof CompositeType) {
                    $values = [];
                    foreach ($type->getKeys($property) as $key) {
                        $mappedKey = $namingStrategy->map($key);
                        $values[] = $input[$mappedKey] ?? null;
                    }
                    $this->writeProperty($object, $property, $type->hydrate($values));
                    continue;
                }

                $value = $input[$namingStrategy->map($property)] ?? null;

                if ($value === null && $type instanceof NullableType && $type->isNullable()) {
                    continue;
                }

                if ($type instanceof EmbedType) {
                    $this->writeProperty(
                        $object,
                        $property,
                        $this->hydrateObject(
                            $value,
                            Instantiator::instantiate($type->getClass())
                        )
                    );
                    continue;
                }

                if ($type instanceof EmbedManyType) {
                    $values = [];
                    if (is_iterable($value)) {
                        foreach ($value as $item) {
                            $values[] = $this->hydrateObject(
                                $item,
                                Instantiator::instantiate($type->getClass())
                            );
                        }
                    }

                    $this->writeProperty($object, $property, $values);
                    continue;
                }

                $this->writeProperty($object, $property, $type->hydrate($value));
            }
        } catch (Throwable $throwable) {
            throw HydrationException::forHydrationError($object, $throwable->getMessage());
        }

        if ($this->identityMap && $id) {
            $this->identityMap->attach($object, $id);
        }

        return $object;
    }

    protected function writeProperty(object $object, string $property, $value): void
    {
        static $writer;
        if ($writer === null) {
            $writer = function ($name, $value) {
                $this->$name = $value;
            };
        }

        $set = Closure::bind($writer, $object, $object);
        $set($property, $value);
    }
}
