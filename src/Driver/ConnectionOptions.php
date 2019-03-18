<?php declare(strict_types=1);

namespace FatCode\Storage\Driver;

interface ConnectionOptions
{
    public function setName(string $name);
    public function getName(): string;
}
