<?php declare(strict_types=1);

namespace FatCode\Storage;

use FatCode\Storage\Exception\SchemaException;

use function in_array;

final class SchemaContainer
{
    /** @var Schema[] */
    private $registry = [];

    /** @var SchemaLoader[] */
    private $loaders = [];

    public function addLoader(SchemaLoader $loader) : void
    {
        $this->loaders[] = $loader;
    }

    public function hasLoader(SchemaLoader $loader) : bool
    {
        return in_array($loader, $this->loaders);
    }

    public function register(Schema $schema) : void
    {
        $this->registry[$schema->getTargetClass()] = $schema;
    }

    public function has(string $class) : bool
    {
        if (!isset($this->registry[$class]) && !$this->load($class)) {
            return false;
        }

        return true;
    }

    public function get(string $class) : Schema
    {
        if (!$this->has($class)) {
            throw SchemaException::forUndefinedSchema($class);
        }

        return $this->registry[$class];
    }

    private function load(string $class) : bool
    {
        foreach ($this->loaders as $loader) {
            $schema = $loader->load($class);
            if ($schema !== null) {
                $this->registry[$class] = $schema;
                return true;
            }
        }

        return false;
    }
}
