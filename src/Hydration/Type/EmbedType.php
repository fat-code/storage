<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

use DateTime;
use DateTimeInterface;
use FatCode\Storage\Exception\TypeException;

class EmbedType implements Type, NullableType
{
    use Nullable;

    private $class;

    public function __construct(string $class)
    {
        if (!class_exists($class)) {
            throw TypeException::forUnknownEmbedClass();
        }
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
