<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Property;

use MongoDB\BSON\Decimal128;
use MongoDB\BSON\ObjectId;
use PHPUnit\Framework\TestCase;
use FatCode\Storage\Hydration\Hydrator;
use FatCode\Storage\Hydration\Instantiator;
use FatCode\Storage\Hydration\Schema\NamingStrategy\UnderscoreNaming;
use FatCode\Storage\Hydration\Schema\SchemaManager;
use FatCode\Storage\Hydration\GenericHydrator;
use Stilus\Tests\Fixtures\UserFixture;

final class GenericHydratorTest extends TestCase
{
    public function setUp() : void
    {
        UserFixture::loadSchema();
    }

    public function testHydrate(): void
    {
        $schema = SchemaManager::get(UserFixture::class);
        $schema->setNamingStrategy(new UnderscoreNaming());
        $hydrator = new class implements Hydrator {
            use GenericHydrator;
        };

        /** @var UserFixture $user */
        $user = $hydrator->hydrate([
            'id' => new ObjectId(),
            'email' => 'test@test.com',
            'age' => 12,
            'wallet_currency' => 'GBP',
            'wallet_amount' => new Decimal128('20.00')
        ], Instantiator::instantiate(UserFixture::class));

        self::assertInstanceOf(UserFixture::class, $user);
        self::assertSame('GBP', $user->getWallet()->getCurrency());
        self::assertSame('20.00', $user->getWallet()->getAmount());
    }
}
