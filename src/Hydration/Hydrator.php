<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

interface Hydrator
{
    public function hydrate(array $hash, object $object = null) : object;
}
