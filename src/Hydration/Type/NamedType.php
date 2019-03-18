<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

interface NamedType extends Type
{
    public function getLocalName(): string;
    public function getExternalName(): string;
}
