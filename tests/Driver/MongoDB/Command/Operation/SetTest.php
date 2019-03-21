<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Operation\Set;
use FatCode\Tests\Storage\Driver\MongoDB\Command\DatabaseHelpers;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;

final class SetTest extends TestCase
{
    use DatabaseHelpers;

    public function testForEach() : void
    {
        $this->generateUsers(10);
        $changed = $this->getConnection()->users->forEach(new Set('name', 'Bob'));
        self::assertSame(10, $changed);
        $users = $this->getConnection()->users->find();
        foreach ($users as $user) {
            self::assertSame('Bob', $user['name']);
        }
    }

    public function testForId() : void
    {
        $id = new ObjectId();
        $this->generateUser(['_id' => $id]);
        $this->getConnection()->users->forId($id, new Set('name', 'Bob'));
        $user = $this->getConnection()->users->get($id);
        self::assertSame('Bob', $user['name']);
    }
}
