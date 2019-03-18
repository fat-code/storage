<?php declare(strict_types=1);

namespace FatCode\Storage;

interface Repository
{
    /**
     * @param Id $id
     * @return object|Storable
     */
    public function get(Id $id): Storable;

    /**
     * @param Storable $entity
     * @return Storable
     */
    public function create(Storable $entity): Storable;

    /**
     * @param Storable $entity
     * @return Storable
     */
    public function remove(Storable $entity): Storable;

    /**
     * @param Storable $entity
     * @return Storable
     */
    public function update(Storable $entity): Storable;
}
