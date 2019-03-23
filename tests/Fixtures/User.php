<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Fixtures;

final class User
{
    private $name;
    private $age;
    private $favouriteNumber;
    private $language;
    private $email;
    private $wallet;
    private $eyeColor;

    public function __construct(UserName $name, UserWallet $wallet)
    {
        $this->name = $name;
        $this->wallet = $wallet;
    }

    public function getName() : UserName
    {
        return $this->name;
    }

    public function getAge() : int
    {
        return $this->age;
    }

    public function getFavouriteNumber() : string
    {
        return $this->favouriteNumber;
    }

    public function getLanguage() : string
    {
        return $this->language;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getWallet() : UserWallet
    {
        return $this->wallet;
    }

    public function getEyeColor() : string
    {
        return $this->eyeColor;
    }
}
