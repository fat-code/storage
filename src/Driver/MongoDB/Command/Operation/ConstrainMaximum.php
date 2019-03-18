<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB\Command\Operation;

/**
 * ConstrainMaximum updates the value of the field to a specified value if the specified value
 * is lower than the current value of the field.
 */
class ConstrainMaximum implements UpdateOperation
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
            '$min' => [
                $this->field => $this->value,
            ],
        ];
    }
}
