<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Driver\MongoDB\Command;

use Faker\Factory as Faker;
use MongoDB\BSON\ObjectId;
use FatCode\Storage\Driver\MongoDB\Command\CreateCollection;
use FatCode\Storage\Driver\MongoDB\Command\DropCollection;
use FatCode\Storage\Driver\MongoDB\Command\Insert;
use FatCode\Storage\Driver\MongoDB\Connection;
use FatCode\Storage\Driver\MongoDB\ConnectionOptions;
use Throwable;

trait CommandTest
{
    /** @var Connection */
    private $connection;

    private function getConnection(): Connection
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        $this->connection = new Connection('localhost', new ConnectionOptions('test'));
        return $this->connection;
    }

    private function createCollection(string $name): void
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

    private function getUsersCollection(): string
    {
        return 'users';
    }

    private function getFavouritesCollection(): string
    {
        return 'user_favourites';
    }

    private function createTestJohn(): array
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

    private function generateUsersAndFavourites(int $userAmount = 100, int $favPerUser = 10): void
    {
        $connection = $this->getConnection();
        $users = $this->generateUsers($userAmount);
        $this->createCollection($this->getFavouritesCollection());
        $faker = Faker::create();
        foreach ($users as $user) {
            for ($i = 0; $i < $favPerUser; $i++) {
                $connection->execute(new Insert($this->getFavouritesCollection(), [
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

    private function generateUsers(int $amount = 100): array
    {
        $connection = $this->getConnection();
        $this->createCollection($this->getUsersCollection());

        $users = [];
        $faker = Faker::create();
        for ($i = 0; $i < $amount; $i++) {
            $id = new ObjectId();
            $connection->execute(new Insert($this->getUsersCollection(), $users[(string) $id] = [
                '_id' => $id,
                'name' => [
                    'first' => $faker->firstName,
                    'last' => $faker->lastName,
                ],
                'language' => $faker->randomElement(['en', 'de', 'pl', 'fr', 'it']),
                'email' => $faker->email,
                'wallet_currency' => $faker->randomElement(['GBP', 'USD', 'EUR']),
                'wallet_amount' => $faker->randomFloat(2, 10, 10000),
                'eye_color' => $faker->colorName,
                'number' => $amount - $i,
                'age' => mt_rand(16, 100),
            ]));
        }

        return $users;
    }
}
