<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Fixtures;

use FatCode\Storage\Hydration\Type;
use FatCode\Storage\Schema;

final class UserSchema extends Schema
{
    protected $name;
    protected $age;
    protected $favouriteNumber;
    protected $language;
    protected $email;
    protected $wallet;
    protected $eyeColor;
    private $notListed;

    public function __construct()
    {
        $this->name = Type::string();
        $this->age = Type::integer();
        $this->favouriteNumber = Type::decimal();
        $this->language = Type::string();
        $this->email = Type::string();
        $this->wallet = Type::embed(new class extends Schema {
            protected $currency;
            protected $amount;
            public function __construct()
            {
                $this->currency = Type::string();
                $this->amount = Type::decimal();
            }

            public function getTargetClass(): string
            {
                return UserWallet::class;
            }
        });
        $this->eyeColor = Type::string();
        $this->notListed = Type::string();
    }

    public function getTargetClass(): string
    {
        return User::class;
    }
}
