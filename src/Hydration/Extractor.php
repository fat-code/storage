<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

interface Extractor
{
    public function extract(object $object) : array;
}
