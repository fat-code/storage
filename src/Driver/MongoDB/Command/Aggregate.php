<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB\Command;

use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\MongoDB\Collation;
use FatCode\Storage\Driver\MongoDB\Command;

final class Aggregate implements Command
{
    private $collection;
    private $pipeline;
    private $collation;

    public function __construct(string $collection, Command\Operation\PipelineOperation ...$pipeline)
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

        $handler($command);
    }
}
