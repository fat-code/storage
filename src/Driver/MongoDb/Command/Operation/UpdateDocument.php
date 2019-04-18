<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

/**
 * Set operator replaces the value of a field with the specified value.
 */
class UpdateDocument implements UpdateOperation
{
    private $document;

    public function __construct(array $document)
    {
        $this->document = $document;
    }

    public function apply() : array
    {
        return $this->document;
    }
}
