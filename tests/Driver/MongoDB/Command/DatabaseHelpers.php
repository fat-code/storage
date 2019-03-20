<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command;

use Faker\Factory as Faker;
use MongoDB\BSON\ObjectId;
use FatCode\Storage\Driver\MongoDb\Command\CreateCollection;
use FatCode\Storage\Driver\MongoDb\Command\DropCollection;
use FatCode\Storage\Driver\MongoDb\Command\Insert;
use FatCode\Storage\Driver\MongoDb\MongoConnection;
use FatCode\Storage\Driver\MongoDb\MongoConnectionOptions;
use Throwable;

trait DatabaseHelpers
{
    /** @var MongoConnection */
    private $connection;

    private function getConnection() : MongoConnection
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        $this->connection = new MongoConnection('localhost', new MongoConnectionOptions('test'));
        return $this->connection;
    }

    private function createCollection(string $name) : void
    {
        $connection = $this->getConnection();
        // Setup collection
        try {
            $connection->execute(new DropCollection($name));
        } catch (Throwable $t) {
            // Ignore.
        }
        $connection->execute(new CreateCollection($name));
    }

    private function generateUsersAndFavourites(int $userAmount = 100, int $favPerUser = 10) : void
    {
        $connection = $this->getConnection();
        $this->createCollection('users');
        $this->createCollection('user_favourites');
        $users = $this->generateUsers($userAmount);
        $faker = Faker::create();
        foreach ($users as $user) {
            for ($i = 0; $i < $favPerUser; $i++) {
                $connection->execute(new Insert('user_favourites', [
                    'user_id' => $user['_id'],
                    'color' => $faker->colorName,
                    'password' => $faker->password,
                    'fruit' => $faker->randomElement(
                        ['banana', 'potato', 'raspberry', 'grapes', 'carrot', 'blueberry']
                    ),
                ]));
            }
        }
    }

    private function generateUsers(int $amount = 100) : array
    {
        $this->createCollection('users');

        $users = [];

        for ($i = 0; $i < $amount; $i++) {
            $user = $this->generateUser(['number' => $amount - $i]);
            $users[(string) $user['_id']] = $user;
        }

        return $users;
    }

    private function generateUser(array $parameters = []) : array
    {
        $faker = Faker::create();
        $id = new ObjectId();
        $user = $parameters + [
            '_id' => $id,
            'name' => [
                'first' => $faker->firstName,
                'last' => $faker->lastName,
            ],
            'fingers' => [1,2,3,4,5],
            'number' => mt_rand(0, 10000),
            'language' => $faker->randomElement(['en', 'de', 'pl', 'fr', 'it']),
            'email' => $faker->email,
            'wallet_currency' => $faker->randomElement(['GBP', 'USD', 'EUR']),
            'wallet_amount' => $faker->randomFloat(2, 10, 10000),
            'eye_color' => $faker->colorName,
            'age' => mt_rand(16, 100),
        ];
        $connection = $this->getConnection();
        $connection->execute(new Insert('users', $user));

        return $user;
    }
}
