<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\NamingStrategy;

class DirectNaming implements NamingStrategy
{
    public function hydrate(string $name) : string
    {
        return $name;
    }

    public function extract(string $name) : string
    {
        return $name;
    }
}
