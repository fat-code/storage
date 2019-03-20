<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Operation\Multiply;
use FatCode\Tests\Storage\Driver\MongoDB\Command\DatabaseHelpers;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;

final class MultiplyTest extends TestCase
{
    use DatabaseHelpers;

    public function testForEach() : void
    {
        $this->generateUsers(10);
        $changed = $this->getConnection()->users->forEach(new Multiply('age', 10));
        self::assertSame(10, $changed);
        $users = $this->getConnection()->users->find();
        foreach ($users as $user) {
            self::assertTrue($user['age'] > 100);
        }
    }

    public function testForId() : void
    {
        $id = new ObjectId();
        $this->generateUser(['age' => 5, '_id' => $id]);
        $this->getConnection()->users->forId($id, new Multiply('age', 4));
        $user = $this->getConnection()->users->get($id);
        self::assertSame(20, $user['age']);
    }
}
