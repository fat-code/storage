<?php declare(strict_types=1);

namespace FatCode\Storage;

use TypeError;
use UnexpectedValueException;
use ReflectionClass;

abstract class Enum
{
    private $value;
    private $key;
    private static $constants = [];
    private static $enums = [];

    protected function __construct(string $key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getKey() : string
    {
        return $this->key;
    }

    public static function __callStatic(string $name, array $arguments = []) : self
    {
        return static::fromKey($name);
    }

    public static function list() : array
    {
        $className = get_called_class();

        if (!isset(self::$constants[$className])) {
            $constants = [];
            $reflection = new ReflectionClass($className);
            foreach ($reflection->getReflectionConstants() as $constant) {
                if ($constant->isPublic()) {
                    $constants[$constant->name] = $constant->getValue();
                }
            }

            self::$constants[$className] = $constants;
        }

        return self::$constants[$className];
    }

    public static function fromValue($value) : self
    {
        $className = get_called_class();
        $map = array_flip(self::values());

        if (!isset($map[$value])) {
            throw new UnexpectedValueException("{$className} does not have passed value in its constants.");
        }
        $key = $map[$value];

        return self::fromKey($key);
    }

    public static function fromKey(string $key) : self
    {
        $className = get_called_class();
        if (!isset(self::$enums[$className])) {
            self::$enums[$className] = [];
        }

        if (isset(self::$enums[$className][$key])) {
            return self::$enums[$className][$key];
        }

        $const = "{$className}::{$key}";
        if (defined($const)) {
            return self::$enums[$className][$key] = new static($key, constant($const));
        }

        throw new TypeError($const . ' is not defined');
    }
}
