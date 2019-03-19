<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB;

use FatCode\Storage\Driver\MongoDb\Command\CreateCollection;
use FatCode\Storage\Driver\MongoDb\Command\DropCollection;
use FatCode\Storage\Driver\MongoDb\MongoConnection;
use FatCode\Storage\Driver\MongoDb\MongoConnectionOptions;
use PHPUnit\Framework\TestCase;

final class ConnectionTest extends TestCase
{
    public function testInstantiate(): void
    {
        self::assertInstanceOf(MongoConnection::class, new MongoConnection('localhost', new MongoConnectionOptions('test')));
    }

    public function testConnect(): void
    {
        $connection = new MongoConnection('localhost', new MongoConnectionOptions('test'));
        self::assertFalse($connection->isConnected());
        $connection->connect();
        self::assertTrue($connection->isConnected());
    }

    public function testClose(): void
    {
        $connection = new MongoConnection('localhost', new MongoConnectionOptions('test'));
        $connection->connect();
        self::assertTrue($connection->isConnected());
        $connection->close();
        self::assertFalse($connection->isConnected());
    }

    public function testExecute(): void
    {
        $connection = new MongoConnection('localhost', new MongoConnectionOptions('test'));

        $cursor = $connection->execute(new CreateCollection('example_collection'));
        self::assertArrayHasKey('ok', $cursor->current());
        self::assertSame(1.0, $cursor->current()['ok']);

        $cursor = $connection->execute(new DropCollection('example_collection'));
        self::assertArrayHasKey('ok', $cursor->current());
        self::assertSame(1.0, $cursor->current()['ok']);
    }
}
