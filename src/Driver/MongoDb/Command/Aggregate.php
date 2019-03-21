<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\MongoDb\Collation;
use FatCode\Storage\Driver\MongoDb\Command\Operation\PipelineOperation;
use FatCode\Storage\Driver\MongoDb\MongoCommand;

final class Aggregate extends MongoCommand
{
    private $collection;
    private $pipeline;
    private $collation;

    public function __construct(string $collection, PipelineOperation ...$pipeline)
    {
        $this->collection = $collection;
        $this->pipeline = $pipeline;
    }

    public function setCollation(Collation $collation): void
    {
        $this->collation = $collation;
    }

    public function execute(callable $handler, Connection $connection): void
    {
        $command = [
            'aggregate' => $this->collection,
            'cursor' => ['batchSize' => 100],
            'pipeline' => [],
        ];
        if (!empty($this->filter)) {
            $command['pipeline'][] = ['$match' => $this->filter];
        }

        foreach ($this->pipeline as $item) {
            $command['pipeline'][] = $item->addToPipeline();
        }
        $this->command = $command;
        $handler($command);
    }
}
