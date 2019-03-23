<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\NamingStrategy;

class MapNaming implements NamingStrategy
{
    private $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function map(string $name) : string
    {
        return isset($this->map[$name]) ? $this->map[$name] : $name;
    }
}
