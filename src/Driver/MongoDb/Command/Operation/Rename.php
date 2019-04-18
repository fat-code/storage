<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

/**
 * Rename operator updates the name of a field
 */
class Rename implements UpdateOperation
{
    private $field;
    private $value;

    public function __construct(string $oldName, string $newName)
    {
        $this->field = $oldName;
        $this->value = $newName;
    }

    public function apply() : array
    {
        return [
            '$rename' => [
                $this->field => $this->value,
            ],
        ];
    }
}
