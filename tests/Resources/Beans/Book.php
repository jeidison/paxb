<?php

namespace Jeidison\PAXB\Tests\Resources\Beans;

use DateTime;
use Jeidison\PAXB\Attributes\Adapters\DateBrAdapter;
use Jeidison\PAXB\Attributes\Adapters\XmlPhpTypeAdapter;
use Jeidison\PAXB\Attributes\XmlAttribute;
use Jeidison\PAXB\Attributes\XmlElement;
use Jeidison\PAXB\Attributes\XmlRootElement;

#[XmlRootElement(name: "livros")]
class Book
{
    #[XmlAttribute(name: "identificador")]
    private int $id;

    #[XmlElement("nome")]
    private String $name;

    #[XmlPhpTypeAdapter(DateBrAdapter::class)]
    private DateTime $date;

    #[XmlElement("author_data")]
    private ?Author $author = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getXmlns(): string
    {
        return $this->xmlns;
    }

    public function setXmlns(string $xmlns): void
    {
        $this->xmlns = $xmlns;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setAuthor(Author $author): void
    {
        $this->author = $author;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

}