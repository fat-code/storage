<?php declare(strict_types=1);

namespace FatCode\Storage;

interface Repository
{
    /**
     * @param mixed $id
     * @return object
     */
    public function get($id) : object;

    /**
     * @param object ...$entity
     * @return object
     */
    public function create(object ...$entity) : object;

    /**
     * @param object ...$entity
     * @return object
     */
    public function remove(object ...$entity) : object;

    /**
     * @param object ...$entity
     * @return object
     */
    public function update(object ...$entity) : object;

    /**
     * @return string
     */
    public function getTargetClass() : string;
}
