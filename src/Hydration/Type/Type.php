<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

interface Type
{
    /**
     * Hydrates value taken from external source hash into the object
     *
     * @param mixed $value
     * @return mixed
     */
    public function hydrate($value);

    /**
     * Extracts value from the object and returns key-value array representing
     * property in the external source.
     *
     * @param mixed $value
     * @return mixed
     */
    public function extract($value);
}
