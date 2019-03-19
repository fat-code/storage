<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

/**
 * Set operator replaces the value of a field with the specified value.
 */
class Set implements UpdateOperation
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
            '$set' => [
                $this->field => $this->value,
            ],
        ];
    }
}
