<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

/**
 * Upsert operator replaces the value of a existing field with the specified value
 * or creates that field if it does not exist.
 */
class Upsert implements UpdateOperation
{
    private $field;
    private $value;

    public function __construct(string $field, $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    public function apply(): array
    {
        return [
            '$setOnInsert' => [
                $this->field => $this->value,
            ],
        ];
    }
}
