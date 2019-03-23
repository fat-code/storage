<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

use ArrayIterator;
use Countable;
use FatCode\Storage\Hydration\NamingStrategy\DirectNaming;
use FatCode\Storage\Hydration\NamingStrategy\NamingStrategy;
use FatCode\Storage\Hydration\Type\IdType;
use FatCode\Storage\Hydration\Type\Type;
use Iterator;
use IteratorAggregate;
use function count;

abstract class Schema implements IteratorAggregate, Countable
{
    private $_properties = [];
    private $_namingStrategy;
    private $_idProperty;

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

    public function definesId() : bool
    {
        $this->build();
        return null !== $this->_idProperty;
    }

    public function getIdName() : string
    {
        if (!$this->definesId()) {
            return '';
        }
        return $this->_idProperty;
    }

    public function getProperties() : array
    {
        $this->build();
        return $this->_properties;
    }

    public function count() : int
    {
        return count($this->getProperties());
    }

    private function build() : void
    {
        if (!empty($this->_properties)) {
            return;
        }

        $properties = get_object_vars($this);
        foreach ($properties as $name => $type) {
            if (!$type instanceof Type) {
                continue;
            }
            if ($type instanceof IdType) {
                $this->_idProperty = $name;
            }
            $this->_properties[$name] = $type;
        }
    }

    abstract public function getTargetClass() : string;
}
