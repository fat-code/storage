<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB\Command\Operation;

/**
 * Rename operator updates the name of a field
 */
class Rename implements UpdateOperation
{
    private $field;
    private $value;

    public function __construct(string $field, string $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    public function apply(): array
    {
        return [
            '$rename' => [
                $this->field => $this->value,
            ],
        ];
    }
}
