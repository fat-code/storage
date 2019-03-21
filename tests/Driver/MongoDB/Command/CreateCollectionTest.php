<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command;

use FatCode\Storage\Driver\MongoDb\Collation;
use FatCode\Storage\Driver\MongoDb\CollationStrength;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;

final class CreateCollectionTest extends TestCase
{
    use DatabaseHelpers;

    public function testCreateCollection() : void
    {
        $name = (string) new ObjectId();
        $connection = $this->getConnection();
        $collections = $connection->listCollections();
        self::assertNotContains($name, $collections);

        $connection->createCollection($name);
        $collections = $connection->listCollections();
        self::assertContains($name, $collections);
    }

    public function testCollectionWithCollation() : void
    {
        $connection = $this->getConnection();
        $name = (string) new ObjectId();
        $connection->createCollection($name, new Collation('en', CollationStrength::LEVEL_3()));
        self::assertContains($name, $connection->listCollections());
    }
}
