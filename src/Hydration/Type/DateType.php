<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

use DateTime;
use DateTimeInterface;

class DateType implements Type, NullableType
{
    use Nullable;

    private $format;

    public function __construct(string $format = 'Ymd')
    {
        $this->format = $format;
    }

    public function hydrate($value): DateTimeInterface
    {
        $date = DateTime::createFromFormat($this->format, (string) $value);
        $date->setTime(0, 0,0);

        return $date;
    }

    public function extract($value): ?string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format($this->format);
        }

        return null;
    }
}
