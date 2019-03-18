<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Tests\Storage\Driver\MongoDB\Command\CommandTest;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;
use FatCode\Storage\Driver\MongoDB\Command\Changeset;
use FatCode\Storage\Driver\MongoDB\Command\Find;
use FatCode\Storage\Driver\MongoDB\Command\Insert;
use FatCode\Storage\Driver\MongoDB\Command\Operation\AddToSet;
use FatCode\Storage\Driver\MongoDB\Command\Operation\ConstrainMaximum;
use FatCode\Storage\Driver\MongoDB\Command\Operation\ConstrainMinimum;
use FatCode\Storage\Driver\MongoDB\Command\Operation\Delete;
use FatCode\Storage\Driver\MongoDB\Command\Operation\Increment;
use FatCode\Storage\Driver\MongoDB\Command\Update;

final class AddToSetTest extends TestCase
{
    use CommandTest;

    public function testAddSingleToSet(): void
    {
        $john = $this->createJohn();
        $connection = $this->getConnection();

        $changeset = Changeset::forId(
            $john['_id'],
            new AddToSet('favourite_colors', 'blue')
        );

        $updateJohn = new Update($this->getUsersCollection(), $changeset);
        $connection->execute($updateJohn);

        $updated = $connection->execute(Find::byId($this->getUsersCollection(), $john['_id']))->current();
        self::assertSame(['red', 'green', 'blue'], $updated['favourite_colors']);
    }

    public function testAddMultipleToSet(): void
    {
        $john = $this->createJohn();
        $connection = $this->getConnection();

        $changeset = Changeset::forId(
            $john['_id'],
            new AddToSet('favourite_colors', 'blue', 'yellow', 'green')
        );

        $updateJohn = new Update($this->getUsersCollection(), $changeset);
        $connection->execute($updateJohn);

        $updated = $connection->execute(Find::byId($this->getUsersCollection(), $john['_id']))->current();
        self::assertSame(['red', 'green', 'blue', 'yellow'], $updated['favourite_colors']);
    }
}
