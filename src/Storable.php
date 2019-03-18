<?php declare(strict_types=1);

namespace FatCode\Storage;

interface Storable
{
    public function getId() : Id;
}
