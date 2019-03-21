<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command;

use FatCode\Storage\Driver\Connection;
use FatCode\Storage\Driver\MongoDb\Collation;
use FatCode\Storage\Driver\MongoDb\Command\Operation\Join;
use FatCode\Storage\Driver\MongoDb\MongoCommand;
use FatCode\Storage\Driver\MongoDb\Command\Operation\FindOperation;
use function array_filter;
use function count;

final class Find extends MongoCommand
{
    private $collection;
    private $filter;
    private $useAggregation;
    private $options;
    /** @var Collation|null */
    private $collation;

    public function __construct(string $collection, array $filter = [], FindOperation ...$options)
    {
        $this->collection = $collection;
        $this->filter = $filter;
        $this->options = $options;
        $hasJoin = function ($option) {
            return $option instanceof Join;
        };
        $this->useAggregation = count(array_filter($options, $hasJoin)) >= 1;
    }

    public function setCollation(Collation $collation): void
    {
        $this->collation = $collation;
    }

    public function execute(callable $handler, Connection $connection): void
    {
        if ($this->useAggregation) {
            (new Aggregate($this->collection, ...$this->options))->execute($handler, $connection);
            return;
        }

        $command = [
            'find' => $this->collection
        ];
        if (!empty($this->filter)) {
            $command['filter'] = $this->filter;
        }
        foreach ($this->options as $option) {
            $command += $option->apply();
        }

        if ($this->collation) {
            $command['collation'] = $this->collation->apply();
        }

        $handler($command);
    }
}
