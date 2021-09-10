<?php

namespace Tests\Resources\Beans;

use DateTime;
use PhpXml\PAXB\Attributes\Adapters\DateTimeBrAdapter;
use PhpXml\PAXB\Attributes\Adapters\XmlPhpTypeAdapter;
use PhpXml\PAXB\Attributes\XmlAttribute;
use PhpXml\PAXB\Attributes\XmlElement;
use PhpXml\PAXB\Attributes\XmlRootElement;

#[XmlRootElement(name: "books")]
class Book
{
    #[XmlAttribute(name: "identificador")]
    private int $id;

    #[XmlAttribute]
    private ?string $xmlns = null;

    #[XmlElement("nome")]
    private String $name;

    #[XmlPhpTypeAdapter(DateTimeBrAdapter::class)]
    private DateTime $date;

    #[XmlElement("author_data")]
    private Author $author;

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