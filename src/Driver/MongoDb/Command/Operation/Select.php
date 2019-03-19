<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

class Select implements FindOperation, PipelineOperation
{
    private $select;

    public function __construct(string ...$fields)
    {
        foreach ($fields as $field) {
            $this->select[$field] = 1;
        }
    }

    public function addToPipeline(): array
    {
        return ['$project' => $this->select];
    }

    public function apply(): array
    {
        return ['projection' => $this->select];
    }
}
