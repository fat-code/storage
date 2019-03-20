<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Operation\ConstrainMinimum;
use FatCode\Storage\Driver\MongoDb\Command\Operation\Sort;
use FatCode\Tests\Storage\Driver\MongoDB\Command\DatabaseHelpers;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;

final class ConstrainMinimumTest extends TestCase
{
    use DatabaseHelpers;

    public function testForEach() : void
    {
        $connection = $this->getConnection();
        $this->generateUser(['age' => 10]);
        $constrained = $connection->users->findOne(
            ['age' => ['$lt' => 20]],
            new Sort('+age')
        );
        self::assertNotNull($constrained);

        $connection->users->forEach(new ConstrainMinimum('age', 20));
        $constrained = $connection->users->findOne(
            ['age' => ['$gt' => 50]],
            new Sort('-age')
        );
        self::assertNull($constrained);
    }

    public function testForId() : void
    {
        $connection = $this->getConnection();
        $id = new ObjectId();
        $this->generateUser(['age' => 10, '_id' => $id]);
        $connection->users->forId($id, new ConstrainMinimum('age', 20));
        $user = $connection->users->get($id);

        self::assertSame(20, $user['age']);
    }
}
