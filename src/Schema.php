<?php declare(strict_types=1);

namespace FatCode\Storage;

use ArrayIterator;
use Countable;
use FatCode\Storage\Hydration\NamingStrategy\DirectNaming;
use FatCode\Storage\Hydration\NamingStrategy\NamingStrategy;
use FatCode\Storage\Hydration\Type\IdType;
use FatCode\Storage\Hydration\Type\Type;
use IteratorAggregate;
use Traversable;

class Schema implements IteratorAggregate, Countable
{
    private $id;
    private $className;
    private $properties = [];
    private $namingStrategy;

    public function __construct(string $className, array $properties = [])
    {
        $this->className = $className;
        $this->properties = $properties;
        $this->namingStrategy = new DirectNaming();
    }

    public function addProperty(string $name, Type $type): void
    {
        if ($type instanceof IdType) {
            $this->id = $name;
        }

        $this->properties[$name] = $type;
    }

    public function hasProperty(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    public function setNamingStrategy(NamingStrategy $namingStrategy): void
    {
        $this->namingStrategy = $namingStrategy;
    }

    public function getNamingStrategy(): NamingStrategy
    {
        return $this->namingStrategy;
    }

    /**
     * @return Type[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->properties);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function hasId(): bool
    {
        return $this->id !== null;
    }

    public function getClass(): string
    {
        return $this->className;
    }

    public function count(): int
    {
        return count($this->properties);
    }
}
