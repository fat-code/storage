<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\NamingStrategy;

class DirectNaming implements NamingStrategy
{
    public function map(string $name) : string
    {
        return $name;
    }
}
