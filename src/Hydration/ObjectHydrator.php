<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

use FatCode\Storage\Exception\SchemaException;

use function in_array;
use function get_class;

final class ObjectHydrator implements Hydrator
{
    use GenericHydrator;

    /** @var Schema[] */
    private $schemaRegistry = [];

    /** @var SchemaLoader[] */
    private $schemaLoaders = [];

    public function __construct(IdentityMap $identityMap = null)
    {
        $this->setIdentityMap($identityMap ?? new IdentityMap());
    }

    public function hydrate(array $hash, object $object) : object
    {
        $className = get_class($object);
        $schema = $this->getSchema($className);

        return $this->hydrateObject($schema, $hash, $object);
    }

    public function addSchemaLoader(SchemaLoader $loader) : void
    {
        if (in_array($loader, $this->schemaLoaders, true)) {
            return;
        }
        $this->schemaLoaders[] = $loader;
    }

    public function addSchema(Schema $schema) : void
    {
        $this->schemaRegistry[$schema->getTargetClass()] = $schema;
    }

    public function hasSchema(string $class) : bool
    {
        return isset($this->schemaRegistry[$class]) || $this->loadSchema($class);
    }

    public function getSchema(string $class) : Schema
    {
        if (!$this->hasSchema($class)) {
            throw SchemaException::forUndefinedSchema($class);
        }

        return $this->schemaRegistry[$class];
    }

    private function loadSchema(string $class) : bool
    {
        foreach ($this->schemaLoaders as $loader) {
            $schema = $loader->load($class);
            if ($schema !== null) {
                $this->schemaRegistry[$class] = $schema;
                return true;
            }
        }

        return false;
    }
}
