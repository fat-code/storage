<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

use FatCode\Storage\Exception\HydrationException;
use Throwable;

use function class_exists;
use function sprintf;
use function strlen;
use function unserialize;

/**
 * Instantiates objects without calling their constructor.
 *
 * @package FatCode\Storage\Hydration
 */
final class Instantiator
{
    private static $instances;

    public static function instantiate(string $className) : object
    {
        if (isset(self::$instances[$className])) {
            return clone self::$instances[$className];
        }

        if (!class_exists($className)) {
            throw HydrationException::forUndeclaredClass($className);
        }

        try {
            self::$instances[$className] = unserialize(
                sprintf('O:%d:"%s":0:{}', strlen($className), $className),
                ['allowed_classes' => [$className]]
            );
        } catch (Throwable $exception) {
            throw HydrationException::forInstantiationError($className, $exception->getMessage());
        }

        return clone self::$instances[$className];
    }
}
