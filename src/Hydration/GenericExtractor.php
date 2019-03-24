<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

use Closure;
use FatCode\Storage\Hydration\Type\CompositeType;

trait GenericExtractor
{
    private function extractObject(Schema $schema, object $object)
    {
        $output = [];
        $namingStrategy = $schema->getNamingStrategy();

        foreach ($schema as $name => $type) {
            $key = $namingStrategy->map($name);
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
