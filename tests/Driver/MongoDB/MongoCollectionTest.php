<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB;

use FatCode\Storage\Driver\MongoDb\MongoCollection;
use FatCode\Tests\Storage\Driver\MongoDB\Command\DatabaseHelpers;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;

final class MongoCollectionTest extends TestCase
{
    use DatabaseHelpers;

    public function testGet() : void
    {
        $id = new ObjectId();
        $this->generateUser(['_id' => $id]);
        $collection = new MongoCollection($this->getConnection(), 'users');
        $user = $collection->get($id);

        self::assertIsArray($user);
        self::assertEquals($id, $user['_id']);
    }

    public function testGetFail() : void
    {
        $collection = new MongoCollection($this->getConnection(), 'users');
        $user = $collection->get(new ObjectId());
        self::assertNull($user);
    }

    public function testInsert() : void
    {
        $id = new ObjectId();
        $collection = new MongoCollection($this->getConnection(), 'users');
        $success = $collection->insert([
            '_id' => $id,
            'name' => 'John',
            'lastName' => 'Doe'
        ]);

        self::assertTrue($success);
        $john = $collection->get($id);
        self::assertEquals($id, $john['_id']);
        self::assertSame('John', $john['name']);
        self::assertSame('Doe', $john['lastName']);
    }
}
