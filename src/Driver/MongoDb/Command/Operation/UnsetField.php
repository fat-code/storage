<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

/**
 * Delete operator deletes the specified field
 */
class UnsetField implements UpdateOperation
{
    private $fields;

    public function __construct(string ...$fields)
    {
        $this->fields = $fields;
    }

    public function apply(): array
    {
        $delete = [];
        foreach ($this->fields as $field) {
            $delete[$field] = '';
        }
        return [
            '$unset' => $delete,
        ];
    }
}
