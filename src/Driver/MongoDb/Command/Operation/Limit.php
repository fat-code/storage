<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

class Limit implements FindOperation, PipelineOperation
{
    private $limit;
    private $offset;

    public function __construct(int $limit, int $offset = null)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function addToPipeline() : array
    {
        if ($this->offset !== null) {
            return ['$limit' => $this->limit, '$skip' => $this->offset];
        }

        return ['$limit' => $this->limit];
    }

    public function apply() : array
    {
        if ($this->offset !== null) {
            return ['limit' => $this->limit, 'skip' => $this->offset];
        }

        return ['limit' => $this->limit];
    }
}
