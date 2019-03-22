<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration;

use FatCode\Storage\Exception\HydrationException;
use FatCode\Storage\Hydration\Instantiator;
use FatCode\Tests\Storage\Fixtures\User;
use FatCode\Tests\Storage\Fixtures\UserName;
use PHPUnit\Framework\TestCase;

final class InstantiatorTest extends TestCase
{
    public function testInstantiate() : void
    {
        self::assertInstanceOf(User::class, Instantiator::instantiate(User::class));
        self::assertInstanceOf(User::class, Instantiator::instantiate(User::class));
        self::assertInstanceOf(UserName::class, Instantiator::instantiate(UserName::class));
    }

    public function testInstantiateInvalidClass() : void
    {
        $this->expectException(HydrationException::class);
        Instantiator::instantiate('____NonExistingClass_____');
    }
}
