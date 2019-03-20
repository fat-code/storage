<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Operation\Increment;
use FatCode\Storage\Driver\MongoDb\Command\Operation\Sort;
use FatCode\Tests\Storage\Driver\MongoDB\Command\DatabaseHelpers;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;

final class IncrementTest extends TestCase
{
    use DatabaseHelpers;

    public function testIncrementForEach() : void
    {
        $this->createCollection('users');
        $this->generateUser(['age' => 11]);
        $this->generateUser(['age' => 12]);
        $this->generateUser(['age' => 13]);
        $changed = $this->getConnection()->users->forEach(new Increment('age', 10));
        self::assertSame(3, $changed);
        $users = $this->getConnection()->users->find([], new Sort('age'));
        foreach ($users as $user) {
            self::assertTrue($user['age'] > 20);
        }
    }

    public function testIncrementForId() : void
    {
        $id = new ObjectId();
        $this->generateUser(['_id' => $id, 'age' => 10]);
        $this->getConnection()->users->forId($id, new Increment('age', -3));
        $user = $this->getConnection()->users->get($id);
        self::assertSame(7, $user['age']);
    }
}
