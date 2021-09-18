<?php

namespace Jeidison\PAXB\Tests\Resources\Beans;

use Jeidison\PAXB\Attributes\XmlElement;

class Author
{
    #[XmlElement]
    private string $name;

    #[XmlElement]
    private ?string $birthday = null;

    #[XmlElement]
    private ?string $email = null;

    private ?Address $address = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getBirthday(): string
    {
        return $this->birthday;
    }

    public function setBirthday(?string $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

}