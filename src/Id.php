<?php declare(strict_types=1);

namespace FatCode\Storage;

interface Id
{
    public function __construct($value);
    public function __toString();
}
