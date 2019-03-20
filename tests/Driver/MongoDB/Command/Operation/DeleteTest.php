<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Operation\ConstrainMaximum;
use FatCode\Storage\Driver\MongoDb\Command\Operation\ConstrainMinimum;
use FatCode\Storage\Driver\MongoDb\Command\Operation\Sort;
use FatCode\Tests\Storage\Driver\MongoDB\Command\DatabaseHelpers;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;

final class DeleteTest extends TestCase
{
    use DatabaseHelpers;

    public function testDeleteForEach() : void
    {

    }

    public function testDeleteForId() : void
    {

    }
}
