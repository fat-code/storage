<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command\Operation;

use FatCode\Storage\Driver\MongoDb\Command\Changeset;
use FatCode\Storage\Driver\MongoDb\Command\Find;
use FatCode\Storage\Driver\MongoDb\Command\Operation\ConstrainMinimum;
use FatCode\Storage\Driver\MongoDb\Command\Update;
use FatCode\Tests\Storage\Driver\MongoDB\Command\CommandTest;
use PHPUnit\Framework\TestCase;

final class ConstrainMinimumTest extends TestCase
{
    use CommandTest;

    public function testConstrain() : void
    {
        $connection = $this->getConnection();
        $this->generateUser(['age' => 18]);
        $cursor = $connection->execute(Find::oneBy($this->getUsersCollection(), ['age' => ['$lt' => 20]]));
        self::assertCount(1, $cursor->toArray());

$update = new Update(
    'users',
    Changeset::forAll(new ConstrainMinimum('age', 20))
);
$connection->execute($update);
        $cursor = $connection->execute(Find::oneBy($this->getUsersCollection(), ['age' => ['$lt' => 20]]));
        self::assertCount(0, $cursor->toArray());
    }
}
