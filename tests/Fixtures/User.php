<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Fixtures;

use DateTime;

final class User
{
    private $name;
    private $age;
    private $favouriteNumber;
    private $language;
    private $email;
    private $wallet;
    private $eyeColor;
    private $creationTime;

    public function __construct(UserName $name, UserWallet $wallet)
    {
        $this->name = $name;
        $this->wallet = $wallet;
        $this->creationTime = new DateTime();
    }

    public function getCreationTime(): DateTime
    {
        return $this->creationTime;
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
