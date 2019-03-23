<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Fixtures;

use FatCode\Storage\Hydration\Type;
use FatCode\Storage\Hydration\Schema;

final class UserSchema extends Schema
{
    protected $id;
    protected $name;
    protected $age;
    protected $favouriteNumber;
    protected $language;
    protected $email;
    protected $wallet;
    protected $eyeColor;
    protected $creationTime;
    private $notListed;

    public function __construct()
    {
        $this->id = Type::id();
        $this->creationTime = Type::dateTime();
        $this->name = Type::embed(new class extends Schema {
            protected $firstName;
            protected $lastName;

            public function __construct()
            {
                $this->firstName = Type::string();
                $this->lastName = Type::string();
            }

            public function getTargetClass(): string
            {
                return UserName::class;
            }
        });
        $this->age = Type::integer();
        $this->favouriteNumber = Type::decimal()->nullable();
        $this->language = Type::string()->nullable();
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
