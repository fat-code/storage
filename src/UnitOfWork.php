<?php declare(strict_types=1);

namespace FatCode\Storage;

interface UnitOfWork
{
    /**
     * Returns already stored entity, entity is retrieved by id.
     * @param string $entityClass
     * @param $id
     * @return object
     */
    public function get(string $entityClass, $id) : object;

    /**
     * Adds entity(ies) for further save
     *
     * @param object ...$entities
     */
    public function persist(object ...$entities) : void;

    /**
     * Adds entity(ies) for further deletion
     * @param object ...$entities
     */
    public function remove(object ...$entities) : void;

    /**
     * Removes and saves previously added entities to the Unit
     */
    public function commit() : void;

    /**
     * Previously added entities will not be persisted nor removed
     */
    public function rollback() : void;

    /**
     * @param object ...$entities
     */
    public function detach(object ...$entities) : void;

    /**
     * @param object ...$entities
     */
    public function attach(object ...$entities) : void;

    /**
     * @param object $entity
     * @return bool
     */
    public function contains(object $entity) : bool;
}
