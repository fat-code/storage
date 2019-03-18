<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

trait Nullable
{
    private $nullable = false;

    public function nullable(): Type
    {
        $this->nullable = true;

        return $this;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
