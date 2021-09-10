<?php

namespace Jeidison\Tests\Resources\Beans;

use Jeidison\PAXB\Attributes\XmlElement;
use Jeidison\PAXB\Attributes\XmlTransient;
use Jeidison\PAXB\Attributes\XmlType;

#[XmlType(propOrder: ["number", "street", "id"])]
class Address
{
    #[XmlTransient]
    private string $id;

    #[XmlElement("street")]
    private string $address;

    #[XmlElement]
    private string $number;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

}