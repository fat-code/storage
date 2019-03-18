<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

use Closure;
use FatCode\Storage\Hydration\Schema\SchemaManager;
use FatCode\Storage\Hydration\Type\CompositeType;
use FatCode\Storage\Hydration\Type\EmbedManyType;
use FatCode\Storage\Hydration\Type\EmbedType;
use FatCode\Storage\Hydration\Type\NamedType;

trait GenericExtractor
{
    public function extract(object $object): array
    {
        return $this->extractObject($object);
    }

    private function extractObject(object $object)
    {
        $schema = SchemaManager::get($object);

        $output = [];
        $namingStrategy = $schema->getNamingStrategy();

        foreach ($schema as $name => $type) {
            $key = $namingStrategy->map($name);
            if ($type instanceof NamedType) {
                $name = $type->getLocalName();
                $key = $type->getExternalName();
            }
            $value = $this->readProperty($object, $name);
            if ($type instanceof CompositeType) {
                $keys = [];
                foreach ($type->getKeys($name) as $key) {
                    $keys[] = $namingStrategy->map($key);
                }

                $output += array_combine(
                    $keys,
                    $type->extract($value)
                );
                continue;
            }


            if ($type instanceof EmbedType) {
                $output[$key] = $this->extractObject($value);
                continue;
            }

            if ($type instanceof EmbedManyType) {
                $iterator = [];
                if (is_iterable($value)) {
                    foreach ($value as $item) {
                        $iterator[] = $this->extractObject($item);
                    }
                }

                $output[$key] = $iterator;
                continue;
            }

            $output[$key] = $type->extract($value);
        }

        return $output;
    }

    protected function readProperty(object $object, string $property)
    {
        static $reader;
        if ($reader === null) {
            $reader = function ($name) {
                return $this->$name;
            };
        }

        $get = Closure::bind($reader, $object, $object);

        return $get($property);
    }
}
