<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\NamingStrategy;

class UnderscoreNaming implements NamingStrategy
{
    public function map(string $name) : string
    {
        return $this->camelCaseToUnderscore($name);
    }

    private function camelCaseToUnderscore(string $input): string
    {
        return ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $input)), '_');
    }
}
