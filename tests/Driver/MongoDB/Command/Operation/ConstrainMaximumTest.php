<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Operation\ConstrainMaximum;
use FatCode\Storage\Driver\MongoDb\Command\Operation\Sort;
use FatCode\Tests\Storage\Driver\MongoDB\Command\DatabaseHelpers;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;

final class ConstrainMaximumTest extends TestCase
{
    use DatabaseHelpers;

    public function testForEach() : void
    {
        $connection = $this->getConnection();
        $this->generateUser(['age' => 100]);
        $olderThan50 = $connection->users->findOne(
            ['age' => ['$gt' => 50]],
            new Sort('-age')
        );
        self::assertNotNull($olderThan50);

        $connection->users->forEach(new ConstrainMaximum('age', 50));
        $olderThan50 = $connection->users->findOne(
            ['age' => ['$gt' => 50]],
            new Sort('-age')
        );
        self::assertNull($olderThan50);
    }

    public function testForId() : void
    {
        $connection = $this->getConnection();
        $id = new ObjectId();
        $this->generateUser(['age' => 100, '_id' => $id]);
        $connection->users->forId($id, new ConstrainMaximum('age', 50));
        $user = $connection->users->get($id);

        self::assertSame(50, $user['age']);
    }
}
