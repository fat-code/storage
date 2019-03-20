<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Operation\Rename;
use FatCode\Tests\Storage\Driver\MongoDB\Command\DatabaseHelpers;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;

final class RenameTest extends TestCase
{
    use DatabaseHelpers;

    public function testForEach() : void
    {
        $this->generateUsers(10);
        $changed = $this->getConnection()->users->forEach(new Rename('eye_color', 'ecolor'));
        self::assertSame(10, $changed);
        $users = $this->getConnection()->users->find(['ecolor' => ['$exists' => true]])->toArray();
        self::assertCount(10, $users);
    }

    public function testForId() : void
    {
        $id = new ObjectId();
        $this->generateUser(['_id' => $id]);
        $this->getConnection()->users->forId($id, new Rename('eye_color', 'ecolor'));
        $user = $this->getConnection()->users->get($id);
        self::assertFalse(isset($user['eye_color']));
        self::assertTrue(isset($user['ecolor']));
    }
}
