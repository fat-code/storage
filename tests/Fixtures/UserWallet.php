<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Fixtures;

final class UserWallet
{
    private $currency;
    private $amount;

    public function __construct(string $currency, string $amount)
    {
        $this->currency = $currency;
        $this->amount = $amount;
    }
}
