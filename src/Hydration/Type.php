<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

use DateTimeZone;
use FatCode\Storage\Hydration\Type\ArrayType;
use FatCode\Storage\Hydration\Type\BooleanType;
use FatCode\Storage\Hydration\Type\DateTimeType;
use FatCode\Storage\Hydration\Type\DateType;
use FatCode\Storage\Hydration\Type\DecimalType;
use FatCode\Storage\Hydration\Type\EmbedType;
use FatCode\Storage\Hydration\Type\FloatType;
use FatCode\Storage\Hydration\Type\IdType;
use FatCode\Storage\Hydration\Type\IntegerType;
use FatCode\Storage\Hydration\Type\SerializationMethod;
use FatCode\Storage\Hydration\Type\StringType;

/**
 * Class Property
 * @package FatCode\Storage\Hydration
 */
final class Type
{
    private function __construct()
    {
        // Prevent for instantiation this class
    }

    public static function integer() : IntegerType
    {
        return new IntegerType();
    }

    public static function string() : StringType
    {
        return new StringType();
    }

    public static function float() : FloatType
    {
        return new FloatType();
    }

    public static function bool() : BooleanType
    {
        return new BooleanType();
    }

    public static function id() : IdType
    {
        return new IdType();
    }

    public static function array(SerializationMethod $method = null) : ArrayType
    {
        return new ArrayType($method);
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
}
