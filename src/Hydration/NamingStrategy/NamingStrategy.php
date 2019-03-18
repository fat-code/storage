<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\NamingStrategy;

interface NamingStrategy
{
    /**
     * Converts given name so it can be hydrated by the Hydrator.
     *
     * @param string $name
     * @return string
     */
    public function map(string $name) : string;
}
