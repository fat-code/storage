<?php declare(strict_types=1);

namespace FatCode\Storage\Driver;

use FatCode\Storage\Hydration\Hydrator;

interface HydratingCursor extends Cursor
{
    public function hydrateWith(Hydrator $hydrator): void;
}
