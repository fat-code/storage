<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Operation\Pop;
use FatCode\Tests\Storage\Driver\MongoDB\Command\DatabaseHelpers;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;

final class PopTest extends TestCase
{
    use DatabaseHelpers;

    public function testPopForEach() : void
    {
        $this->generateUsers(10);
        $changed = $this->getConnection()->users->forEach(new Pop('fingers'));
        self::assertSame(10, $changed);
        $users = $this->getConnection()->users->find();
        foreach ($users as $user) {
            self::assertCount(4, $user['fingers']);
            self::assertNotContains(5, $user['fingers']);
        }
    }

    public function testMultiplyForId() : void
    {
        $id = new ObjectId();
        $this->generateUser(['_id' => $id]);
        $this->getConnection()->users->forId($id, new Pop('fingers'));
        $user = $this->getConnection()->users->get($id);
        self::assertCount(4, $user['fingers']);
        self::assertNotContains(5, $user['fingers']);
    }
}
