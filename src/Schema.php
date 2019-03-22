<?php declare(strict_types=1);

namespace FatCode\Storage;

use ArrayIterator;
use Countable;
use FatCode\Storage\Hydration\NamingStrategy\DirectNaming;
use FatCode\Storage\Hydration\NamingStrategy\NamingStrategy;
use FatCode\Storage\Hydration\Type\Type;
use Iterator;
use IteratorAggregate;
use function count;

abstract class Schema implements IteratorAggregate, Countable
{
    private $_properties = [];
    private $_namingStrategy;

    public function getIterator() : Iterator
    {
        return new ArrayIterator($this->getProperties());
    }

    public function getNamingStrategy() : NamingStrategy
    {
        if (null === $this->_namingStrategy) {
            $this->_namingStrategy = new DirectNaming();
        }

        return $this->_namingStrategy;
    }

    public function getProperties() : array
    {
        if (empty($this->_properties)) {
            $this->build();
        }

        return $this->_properties;
    }

    public function count() : int
    {
        return count($this->getProperties());
    }

    private function build() : void
    {
        $properties = get_object_vars($this);
        foreach ($properties as $name => $type) {
            if (!$type instanceof Type) {
                continue;
            }
            $this->_properties[$name] = $type;
        }
    }

    abstract public function getTargetClass() : string;
}
