<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Changeset;
use FatCode\Storage\Driver\MongoDb\Command\Find;
use FatCode\Storage\Driver\MongoDb\Command\Operation\ConstrainMaximum;
use FatCode\Storage\Driver\MongoDb\Command\Operation\Delete;
use FatCode\Storage\Driver\MongoDb\Command\Update;
use FatCode\Storage\Driver\MongoDb\MongoDb;
use FatCode\Tests\Storage\Driver\MongoDB\Command\CommandTest;
use PHPUnit\Framework\TestCase;

final class DeleteTest extends TestCase
{
    use CommandTest;

    public function testDelete() : void
    {
        $connection = $this->getConnection();
        $user = $this->generateUser();
        $changeset = Changeset::forAll(new Delete('name'));
        $connection->execute(new Update($this->getUsersCollection(), $changeset));

        $updatedUser = $connection->execute(Find::byId($this->getUsersCollection(), $user['_id']))->current();

        $a = 1;

        $mongo = new MongoDb($connection);
        $mongo->users->apply(Changeset::forAll(new Delete('name')));
    }
}
