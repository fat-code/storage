<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\NamingStrategy;

interface NamingStrategy
{
    /**
     * Converts given name so it can be hydrated or extracted. Returned name should be valid name kept in your
     * persistence layer.
     *
     * @param string $name
     * @return string
     */
    public function map(string $name) : string;
}
