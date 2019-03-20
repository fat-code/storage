<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Operation\Unshift;
use FatCode\Tests\Storage\Driver\MongoDB\Command\DatabaseHelpers;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;

final class UnshiftTest extends TestCase
{
    use DatabaseHelpers;

    public function testForEach() : void
    {
        $this->generateUsers(10);
        $changed = $this->getConnection()->users->forEach(new Unshift('fingers'));
        self::assertSame(10, $changed);
        $users = $this->getConnection()->users->find();
        foreach ($users as $user) {
            self::assertCount(4, $user['fingers']);
            self::assertNotContains(1, $user['fingers']);
        }
    }

    public function testForId() : void
    {
        $id = new ObjectId();
        $this->generateUser(['_id' => $id]);
        $this->getConnection()->users->forId($id, new Unshift('fingers'));
        $user = $this->getConnection()->users->get($id);
        self::assertCount(4, $user['fingers']);
        self::assertNotContains(1, $user['fingers']);
    }
}
