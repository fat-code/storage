<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

class Match implements PipelineOperation
{
    private $query;

    public function __construct(array $query)
    {
        $this->query = $query;
    }

    public function addToPipeline(): array
    {
        return ['$match' => $this->query];
    }
}
