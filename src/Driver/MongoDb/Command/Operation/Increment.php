<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

/**
 * Increment operator increments a field by a specified value
 * If the field does not exist, increment creates the field and sets the field to the specified value.
 * Use of the increment operator on a field with a null value will generate an error.
 */
class Increment implements UpdateOperation
{
    private $field;
    private $value;

    public function __construct(string $field, int $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    public function apply(): array
    {
        return [
            '$inc' => [
                $this->field => $this->value,
            ],
        ];
    }
}
