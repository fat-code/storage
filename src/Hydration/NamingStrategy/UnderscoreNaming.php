<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\NamingStrategy;

class UnderscoreNaming implements NamingStrategy
{
    public function extract(string $name) : string
    {
        return $this->camelCaseToUnderscore($name);
    }

    public function hydrate(string $name) : string
    {
        $name = str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
        $name[0] = strtolower($name[0]);

        return $name;
    }

    private function camelCaseToUnderscore(string $input): string
    {
        return ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $input)), '_');
    }
}
