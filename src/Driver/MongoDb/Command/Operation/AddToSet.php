<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

/**
 * AddToSet operator adds a value to an array unless the value is already present,
 * in which case AddToSet does nothing to that array.
 */
class AddToSet implements UpdateOperation
{
    private $field;
    private $values;

    public function __construct(string $field, ...$values)
    {
        $this->field = $field;
        $this->values = $values;
    }

    public function apply() : array
    {

        if (count($this->values) > 1) {
            $add = [
                '$each' => $this->values
            ];
        } else {
            $add = $this->values[0];
        }

        return [
            '$addToSet' => [
                $this->field => $add,
            ],
        ];
    }
}
