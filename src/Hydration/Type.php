<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

use DateTimeZone;
use FatCode\Storage\Exception\TypeException;
use FatCode\Storage\Hydration\Type\BooleanType;
use FatCode\Storage\Hydration\Type\DateTimeType;
use FatCode\Storage\Hydration\Type\DateType;
use FatCode\Storage\Hydration\Type\DecimalType;
use FatCode\Storage\Hydration\Type\EmbedType;
use FatCode\Storage\Hydration\Type\FloatType;
use FatCode\Storage\Hydration\Type\IdType;
use FatCode\Storage\Hydration\Type\IntegerType;
use FatCode\Storage\Hydration\Type\StringType;
use FatCode\Storage\Hydration\Type\Type as BaseType;
use ReflectionClass;

/**
 * Class Property
 * @package FatCode\Storage\Hydration
 */
final class Type
{
    /**
     * @var ReflectionClass[]
     */
    private static $reflections = [];

    private static $types = [
        'int' => IntegerType::class,
        'string' => StringType::class,
        'float' => FloatType::class,
        'id' => IdType::class,
        'decimal' => DecimalType::class,
        'date' => DateType::class,
        'dateTime' => DateTimeType::class,
    ];

    private function __construct()
    {
        // Prevent for instantiation this class
    }

    public static function integer() : IntegerType
    {
        static $type;
        if ($type === null) {
            $type = new IntegerType();
        }
        return $type;
    }

    public static function string() : StringType
    {
        static $type;
        if ($type === null) {
            $type = new StringType();
        }
        return $type;
    }

    public static function float() : FloatType
    {
        static $type;
        if ($type === null) {
            $type = new FloatType();
        }
        return $type;
    }

    public static function bool() : BooleanType
    {
        static $type;
        if ($type === null) {
            $type = new BooleanType();
        }
        return $type;
    }

    public static function id() : IdType
    {
        static $type;
        if ($type === null) {
            $type = new IdType();
        }
        return $type;
    }

    public static function decimal(int $scale = 2, int $precision = 10) : DecimalType
    {
        return new DecimalType($scale, $precision);
    }

    public static function date(string $format = 'Ymd') : DateType
    {
        return new DateType($format);
    }

    public static function embed(Schema $schema) : EmbedType
    {
        return new EmbedType($schema);
    }

    public static function dateTime(DateTimeZone $defaultTimeZone = null) : DateTimeType
    {
        return new DateTimeType($defaultTimeZone);
    }

    public static function registerType(string $name, string $class) : void
    {
        if (!is_subclass_of($class, Type::class)) {
            throw TypeException::forInvalidTypeRegister($class);
        }

        self::$types[$name] = $class;
    }

    public static function __callStatic($name, $arguments) : BaseType
    {
        if (!isset(self::$types[$name])) {
            throw TypeException::forUnknownType($name);
        }

        $class = self::$types[$name];
        if (!isset(self::$reflections[$name])) {
            return self::$reflections[$name] = new ReflectionClass($class);
        }

        return self::$reflections[$name]->newInstanceArgs($arguments);
    }
}
