<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb\Command\Operation;

interface PipelineOperation
{
    public function addToPipeline() : array;
}
