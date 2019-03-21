<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\NamingStrategy;

class MapNaming implements NamingStrategy
{
    private $hydrationMap;
    private $extractionMap;

    public function __construct(array $map)
    {
        $this->hydrationMap = $map;
        $this->extractionMap = array_flip($map);
    }

    public function hydrate(string $name) : string
    {
        return isset($this->hydrationMap[$name]) ? $this->hydrationMap[$name] : $name;
    }

    public function extract(string $name): string
    {
        return isset($this->extractionMap[$name]) ? $this->extractionMap[$name] : $name;
    }
}
