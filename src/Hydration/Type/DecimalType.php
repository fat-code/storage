<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

use FatCode\Storage\Exception\TypeException;

use function bcadd;
use function strlen;

class DecimalType implements Type, NullableType
{
    use Nullable;

    private $scale;
    private $precision;

    public function __construct(int $scale = 2, int $precision = 10)
    {
        $this->scale = $scale;
        $this->precision = $precision;
        $this->validate();
    }

    public function hydrate($value): string
    {
        return $this->formatNumber((string) $value);
    }

    public function extract($value): string
    {
        return $this->formatNumber((string) $value);
    }

    private function formatNumber(string $number): string
    {
        $parts = explode('.', $number);
        $decimals = $this->precision - $this->scale;

        if (strlen($parts[0]) > $decimals) {
            $parts[0] = str_repeat('9', $decimals);
        }

        $number = $parts[0] . '.' . ($parts[1] ?? '');

        return bcadd($number, '0', $this->scale);
    }

    private function validate(): void
    {
        if ($this->scale > $this->precision) {
            throw TypeException::forValidationFailure(
                $this,
                'Attribute `scale` must be lower than `precision`.'
            );
        }
    }
}
