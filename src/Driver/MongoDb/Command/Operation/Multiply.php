<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

use function is_float;
use function is_int;
use FatCode\Storage\Exception\DriverException;

/**
 * Delete operator deletes the specified field
 */
class Multiply implements UpdateOperation
{
    private $field;

    private $value;

    /**
     * Delete constructor.
     * @param string $field
     * @param int|float $value
     */
    public function __construct(string $field, $value)
    {
        if (!is_float($value) && is_int($value)) {
            throw DriverException::forOperationFailure($this, 'passed value type must be either int or float');
        }

        $this->field = $field;
        $this->value = $value;
    }

    public function apply(): array
    {
        return [
            '$mul' => [
                $this->field => $this->value,
            ],
        ];
    }
}
