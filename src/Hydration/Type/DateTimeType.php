<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

class DateTimeType implements CompositeType, NullableType
{
    use Nullable;
    
    private const DATE_PART = 'Date';
    private const TIME_PART = 'Time';
    private const TIMEZONE_PART = 'Timezone';

    private $defaultTimezone;

    public function __construct(DateTimeZone $defaultTimezone = null)
    {
        $this->defaultTimezone = $defaultTimezone ?? new DateTimeZone('UTC');
    }

    public function hydrate($value): DateTimeInterface
    {
        return new DateTimeImmutable(
            "@{$value[0]}",
            isset($value[1]) ? new DateTimeZone($value[1]) : $this->defaultTimezone
        );
    }

    /**
     * @param DateTimeInterface|null $object
     * @return array
     */
    public function extract($object): array
    {
        if ($object === null) {
            return [null, $this->defaultTimezone];
        }

        return [
            $object->getTimestamp(),
            $object->getTimezone()->getName()
        ];
    }

    public function getKeys(string $prefix): array
    {
        return [
            $prefix . self::DATE_PART,
            $prefix . self::TIME_PART,
            $prefix . self::TIMEZONE_PART,
        ];
    }
}
