<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Type;

use FatCode\Storage\Hydration\Type\EmbedType;
use FatCode\Tests\Storage\Fixtures\User;
use FatCode\Tests\Storage\Fixtures\UserName;
use FatCode\Tests\Storage\Fixtures\UserSchema;
use FatCode\Tests\Storage\Fixtures\UserWallet;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;

final class EmbedTypeTest extends TestCase
{
    public function testHydrate() : void
    {
        $type = new EmbedType(new UserSchema());
        /** @var User $hydrated */
        $hydrated = $type->hydrate([
            'id' => new ObjectId(),
            'name' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
            'wallet' => [
                'amount' => '1000.20',
                'currency' => 'EUR',
            ],
            'age' => '15',
            'creationTimeDate' => time(),
            'creationTimeTimezone' => 'UTC',
        ]);

        self::assertInstanceOf(User::class, $hydrated);
        self::assertSame(15, $hydrated->getAge());
        self::assertInstanceOf(UserWallet::class, $hydrated->getWallet());
        self::assertSame('1000.20', $hydrated->getWallet()->getAmount());
        self::assertSame('EUR', $hydrated->getWallet()->getCurrency());
        self::assertInstanceOf(UserName::class, $hydrated->getName());
        self::assertSame('John', $hydrated->getName()->getFirstName());
        self::assertSame('Doe', $hydrated->getName()->getLastName());
    }
}
