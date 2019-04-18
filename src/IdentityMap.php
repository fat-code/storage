<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

use FatCode\Storage\Exception\IdentityMapException;

final class IdentityMap
{
    private $objects;

    public function __construct()
    {
        $this->objects = [];
    }

    public function attach(object $object, $id) : void
    {
        $this->objects[(string) $id] = $object;
    }

    public function detach($id) : void
    {
        if ($this->has($id)) {
            unset($this->objects[(string)$id]);
        }
    }

    public function clear() : void
    {
        $this->objects = [];
    }

    public function isEmpty() : bool
    {
        return empty($this->objects);
    }

    public function has($id) : bool
    {
        return isset($this->objects[(string) $id]);
    }

    public function get($id) : object
    {
        if (!$this->has($id)) {
            throw IdentityMapException::forMissingObject($id);
        }

        return $this->objects[(string) $id];
    }
}
