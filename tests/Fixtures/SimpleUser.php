<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Fixtures;

final class SimpleUser
{
    private $name;
    private $email;
    private $age;
    private $id;

    public function __construct(UserName $name, string $email, int $age)
    {
        $this->id = new ObjectId();
        $this->name = $name;
        $this->email = $email;
        $this->age = $age;
    }
}
