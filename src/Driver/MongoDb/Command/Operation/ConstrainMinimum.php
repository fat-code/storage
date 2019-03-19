<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

/**
 * ConstrainMinimum updates the value of the field to a specified value if the specified value
 * is greater than the current value of the field.
 */
class ConstrainMinimum implements UpdateOperation
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
            '$max' => [
                $this->field => $this->value,
            ],
        ];
    }
}
