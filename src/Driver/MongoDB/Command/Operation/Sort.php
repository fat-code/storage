<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB\Command\Operation;

class Sort implements FindOperation, PipelineOperation
{
    private $sort;

    /**
     * @param string ...$fields
     * @example
     * new Sort('-name', '+age',' 'date');// Will sort results by name (desc), age (asc), date (asc)
     */
    public function __construct(string ...$fields)
    {
        foreach ($fields as $field) {
            switch ($field[0]) {
                case '-':
                    $this->sort[substr($field, 1)] = -1;
                    break;
                case '+':
                    $this->sort[substr($field, 1)] = 1;
                    break;
                default:
                    $this->sort[$field] = 1;
                    break;
            }
        }
    }

    public function addToPipeline(): array
    {
        return ['$sort' => $this->sort];
    }

    public function apply(): array
    {
        return ['sort' => $this->sort];
    }
}
