<?php declare(strict_types=1);

namespace FatCode\Storage;

final class Storage implements UnitOfWork
{
    public function get(string $targetClass, $id) : object
    {
    }

    public function persist(object ...$entities) : void
    {
    }

    public function remove(object ...$entities) : void
    {
    }

    public function commit() : void
    {
    }

    public function rollback() : void
    {
    }

    public function detach(object ...$entities) : void
    {
    }

    public function attach(object ...$entities) : void
    {
    }

    public function contains(object $entity) : bool
    {
    }
}
