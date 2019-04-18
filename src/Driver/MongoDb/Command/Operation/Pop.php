<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

/**
 * The pop operator removes the last element of an array.
 */
class Pop implements UpdateOperation
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
                $this->field => 1,
            ],
        ];
    }
}
