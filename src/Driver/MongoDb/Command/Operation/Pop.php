<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

/**
 * The pop operator removes the first or last element of an array.
 * Pass 1 as value to remove the first element pass -1 to remove
 * last element.
 */
class Pop implements UpdateOperation
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
            '$pop' => [
                $this->field => $this->value,
            ],
        ];
    }
}
