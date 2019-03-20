<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Operation\UnsetField;
use FatCode\Tests\Storage\Driver\MongoDB\Command\DatabaseHelpers;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;

final class UnsetFieldTest extends TestCase
{
    use DatabaseHelpers;

    public function testDeleteForEach() : void
    {
        $this->generateUsers(10);
        $changed = $this->getConnection()->users->forEach(new UnsetField('eye_color'));
        self::assertSame(10, $changed);
        $users = $this->getConnection()->users->find(['eye_color' => ['$exists' => false]])->toArray();
        self::assertCount(10, $users);
    }

    public function testDeleteForId() : void
    {
        $id = new ObjectId();
        $this->generateUser(['_id' => $id]);
        $this->getConnection()->users->forId($id, new UnsetField('eye_color'));
        $user = $this->getConnection()->users->get($id);
        self::assertFalse(isset($user['eye_color']));
    }
}
