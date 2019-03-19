<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Changeset;
use FatCode\Storage\Driver\MongoDb\Command\Find;
use FatCode\Storage\Driver\MongoDb\Command\Operation\ConstrainMaximum;
use FatCode\Storage\Driver\MongoDb\Command\Operation\Sort;
use FatCode\Storage\Driver\MongoDb\Command\Update;
use FatCode\Tests\Storage\Driver\MongoDB\Command\CommandTest;
use PHPUnit\Framework\TestCase;

final class ConstrainMaximumTest extends TestCase
{
    use CommandTest;

    public function testConstrainWithSimpleInterface() : void
    {
        $connection = $this->getConnection();
        $this->generateUser(['age' => 100]);
        $olderThan50 = $connection->users->findOne(
            ['age' => ['$gt' => 50]],
            new Sort('-age')
        );
        self::assertNotNull($olderThan50);

        $connection->users->forEach(new ConstrainMaximum('age', 50));
    }
}
