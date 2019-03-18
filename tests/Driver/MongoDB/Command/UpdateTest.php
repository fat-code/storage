<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command;

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

final class UpdateTest extends TestCase
{
    use CommandTest;

    public function testUpdateDocument(): void
    {
        $this->createCollection($this->getUsersCollection());

        $john = $this->createJohn();
        $john['eye_color'] = 'blue';

        $connection = $this->getConnection();
        $updateJohn = Update::forDocument($this->getUsersCollection(), $john);
        $connection->execute($updateJohn);

        $findJohn = Find::byId($this->getUsersCollection(), $john['_id']);
        $foundJohn = $connection->execute($findJohn)->current();

        self::assertSame('blue', $foundJohn['eye_color']);
    }

    public function testUpdateMultipleDocuments(): void
    {
        $connection = $this->getConnection();
        $users = $this->generateUsers(10);

        $updates = [];
        foreach ($users as $user) {
            $user['updated'] = true;
            $updates[] = $user;
        }

        $updateUsers = Update::forDocuments($this->getUsersCollection(), ...$updates);
        $connection->execute($updateUsers);
        $findAll = new Find($this->getUsersCollection());
        $foundUsers = $connection->execute($findAll)->toArray();

        foreach ($foundUsers as $user) {
            self::assertArrayHasKey('updated', $user);
            self::assertTrue($user['updated']);
        }
    }

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

    public function testDeleteSingleField(): void
    {
        $john = $this->createJohn();
        $connection = $this->getConnection();

        $changeset = Changeset::forId(
            $john['_id'],
            new Delete('favourite_colors')
        );

        $updateJohn = new Update($this->getUsersCollection(), $changeset);
        $connection->execute($updateJohn);

        $updated = $connection->execute(Find::byId($this->getUsersCollection(), $john['_id']))->current();
        self::assertArrayNotHasKey('favourite_colors', $updated);
        self::assertArrayHasKey('name', $updated);
        self::assertArrayHasKey('number', $updated);
        self::assertArrayHasKey('wallet', $updated);
        self::assertArrayHasKey('eye_color', $updated);
    }

    public function testDeleteMultipleFields(): void
    {
        $john = $this->createJohn();
        $connection = $this->getConnection();

        $changeset = Changeset::forId(
            $john['_id'],
            new Delete('favourite_colors', 'number', 'wallet', 'eye_color')
        );

        $updateJohn = new Update($this->getUsersCollection(), $changeset);
        $connection->execute($updateJohn);

        $updated = $connection->execute(Find::byId($this->getUsersCollection(), $john['_id']))->current();
        self::assertArrayNotHasKey('favourite_colors', $updated);
        self::assertArrayHasKey('name', $updated);
        self::assertArrayNotHasKey('number', $updated);
        self::assertArrayNotHasKey('wallet', $updated);
        self::assertArrayNotHasKey('eye_color', $updated);
    }

    public function testIncrementValue(): void
    {
        $john = $this->createJohn();
        $connection = $this->getConnection();

        $changeset = Changeset::forId(
            $john['_id'],
            new Increment('number', 20)
        );

        $updateJohn = new Update($this->getUsersCollection(), $changeset);
        $connection->execute($updateJohn);

        $updated = $connection->execute(Find::byId($this->getUsersCollection(), $john['_id']))->current();
        self::assertSame(30, $updated['number']);
    }

    public function testIncrementMultipleValues(): void
    {
        $john = $this->createJohn();
        $connection = $this->getConnection();

        $changeset = Changeset::forId(
            $john['_id'],
            new Increment('number', 20),
            new Increment('wallet.amount', 10)
        );

        $updateJohn = new Update($this->getUsersCollection(), $changeset);
        $connection->execute($updateJohn);

        $updated = $connection->execute(Find::byId($this->getUsersCollection(), $john['_id']))->current();
        self::assertSame(30, $updated['number']);
        self::assertSame(30.0, $updated['wallet']['amount']);
    }

    public function testConstrainMaximum(): void
    {
        $john = $this->createJohn();
        $connection = $this->getConnection();

        $changeset = Changeset::forId(
            $john['_id'],
            new ConstrainMaximum('number', 2),
            new ConstrainMaximum('wallet.amount', 12.0)
        );

        $updateJohn = new Update($this->getUsersCollection(), $changeset);
        $connection->execute($updateJohn);

        $updated = $connection->execute(Find::byId($this->getUsersCollection(), $john['_id']))->current();
        self::assertSame(2, $updated['number']);
        self::assertSame(12.0, $updated['wallet']['amount']);
    }

    public function testConstrainMinimum(): void
    {
        $john = $this->createJohn();
        $connection = $this->getConnection();

        $changeset = Changeset::forId(
            $john['_id'],
            new ConstrainMinimum('number', 11),
            new ConstrainMinimum('wallet.amount', 50.0)
        );

        $updateJohn = new Update($this->getUsersCollection(), $changeset);
        $connection->execute($updateJohn);

        $updated = $connection->execute(Find::byId($this->getUsersCollection(), $john['_id']))->current();
        self::assertSame(11, $updated['number']);
        self::assertSame(50.0, $updated['wallet']['amount']);
    }

    private function createJohn(): array
    {
        $connection = $this->getConnection();
        $john = [
            '_id' => new ObjectId(),
            'name' => [
                'first' => 'John',
                'last' => 'Doe',
            ],
            'number' => 10,
            'language' => 'pl',
            'eye_color' => 'brown',
            'wallet' => [
                'currency' => 'EUR',
                'amount' => 20.00,
            ],
            'favourite_colors' => ['red', 'green']
        ];
        $createJohn = new Insert($this->getUsersCollection(), $john);
        $connection->execute($createJohn);

        return $john;
    }
}
