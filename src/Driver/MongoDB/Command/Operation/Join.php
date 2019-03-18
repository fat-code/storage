<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB\Command\Operation;

class Join implements FindOperation, PipelineOperation
{
    protected $join;

    public function __construct(string $from, string $localKey, string $foreignKey, string $as = null)
    {
        $this->join = [
            'from' => $from,
            'localField' => $localKey,
            'foreignField' => $foreignKey,
            'as' => $as ?? $from
        ];
    }

    public function addToPipeline(): array
    {
        return ['$lookup' => $this->join];
    }

    public function apply(): array
    {
        return [];
    }
}
