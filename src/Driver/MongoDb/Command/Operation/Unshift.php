<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

/**
 * The unshift operator removes the first element of an array.
 */
class Unshift implements UpdateOperation
{
    private $field;

    public function __construct(string $field)
    {
        $this->field = $field;
    }

    public function apply() : array
    {
        return [
            '$pop' => [
                $this->field => -1,
            ],
        ];
    }
}
