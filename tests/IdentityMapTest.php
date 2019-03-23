<?php declare(strict_types=1);

namespace FatCode\Tests\Storage;

use FatCode\Storage\Exception\IdentityMapException;
use FatCode\Storage\Hydration\IdentityMap;
use FatCode\Storage\Hydration\Instantiator;
use FatCode\Tests\Storage\Fixtures\User;
use PHPUnit\Framework\TestCase;
use stdClass;

final class IdentityMapTest extends TestCase
{
    public function testAttach() : void
    {
        $testInstance = Instantiator::instantiate(User::class);
        $identityMap = new IdentityMap();

        self::assertFalse($identityMap->has('testid'));
        $identityMap->attach($testInstance, 'testid');
        self::assertTrue($identityMap->has('testid'));
        self::assertSame($testInstance, $identityMap->get('testid'));
    }

    public function testIsEmpty() : void
    {
        $identityMap = new IdentityMap();
        self::assertTrue($identityMap->isEmpty());
        $identityMap->attach(new stdClass(), 'id');
        self::assertFalse($identityMap->isEmpty());
    }

    public function testDetach() : void
    {
        $testInstance = Instantiator::instantiate(User::class);
        $identityMap = new IdentityMap();
        self::assertFalse($identityMap->has('testid'));
        $identityMap->attach($testInstance, 'testid');
        self::assertTrue($identityMap->has('testid'));
        $identityMap->detach('testid');
        self::assertFalse($identityMap->has('testid'));
        self::assertTrue($identityMap->isEmpty());
    }

    public function testClear() : void
    {
        $testInstance = Instantiator::instantiate(User::class);
        $identityMap = new IdentityMap();
        $identityMap->attach($testInstance, 'testid');
        $identityMap->clear();
        self::assertTrue($identityMap->isEmpty());
    }

    public function testFailGetOnNonAttachedItem() : void
    {
        $this->expectException(IdentityMapException::class);
        $identityMap = new IdentityMap();
        $identityMap->get('testid');
    }
}
