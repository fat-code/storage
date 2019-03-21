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
    public function hydrate(string $name) : string;

    /**
     * Converts given name so it can be extracted by the Extractor.
     *
     * @param string $name
     * @return string
     */
    public function extract(string $name) : string;
}
