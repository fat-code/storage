<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Changeset;
use FatCode\Storage\Driver\MongoDb\Command\Find;
use FatCode\Storage\Driver\MongoDb\Command\Operation\AddToSet;
use FatCode\Storage\Driver\MongoDb\Command\Operation\Limit;
use FatCode\Storage\Driver\MongoDb\Command\Update;
use FatCode\Tests\Storage\Driver\MongoDB\Command\CommandTest;
use PHPUnit\Framework\TestCase;

final class AddToSetTest extends TestCase
{
    use CommandTest;

    public function testAddToSetUsingSimpleInterface() : void
    {
        $connection = $this->getConnection();
        $user = $this->generateUser(['favourite_colors' => ['red', 'green']]);

        $connection->users->forId(
            $user['_id'],
            new AddToSet('favourite_colors', 'blue')
        );

        $updated = $connection->users->get($user['_id']);
        self::assertSame(['red', 'green', 'blue'], $updated['favourite_colors']);
    }

    public function testAddToSet() : void
    {
        $connection = $this->getConnection();
        $user = $this->generateUser(['favourite_colors' => ['red', 'green']]);

        $changeset = new Changeset(
            ['_id' => $user['_id']],
            new AddToSet('favourite_colors', 'blue')
        );
        $update = new Update('users', $changeset);
        $connection->execute($update);

        $updated = $connection->execute(new Find(
            'users',
            ['_id' => $user['_id']],
            new Limit(1)
        ))->toArray()[0];
        self::assertSame(['red', 'green', 'blue'], $updated['favourite_colors']);
    }

    public function testAddMultipleToSet() : void
    {
        $connection = $this->getConnection();
        $user = $this->generateUser(['favourite_colors' => ['red', 'green']]);

        $connection->users->forId(
            $user['_id'],
            new AddToSet('favourite_colors', 'blue', 'yellow')
        );

        $updated = $connection->users->get($user['_id']);
        self::assertSame(['red', 'green', 'blue', 'yellow'], $updated['favourite_colors']);
    }
}
