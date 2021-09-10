<?php

namespace Tests\Unit;

use DateTime;
use PhpXml\PAXB\PAXB;
use Tests\Resources\Beans\Address;
use Tests\Resources\Beans\Author;
use Tests\Resources\Beans\Book;
use Tests\TestCase;

class PAXBTest extends TestCase
{
    public function testMarshal()
    {
        $address = new Address();
        $address->setAddress("Rua 10");
        $address->setNumber("123");
        $address->setId("123");

        $author = new Author();
        $author->setName("Jeidison Farias");
        $author->setBirthday("");
        $author->setEmail("");
        $author->setAddress($address);

        $book = new Book();
        $book->setId(1);
        $book->setName("PHPBX - XML Binding");
        $book->setAuthor($author);
        $book->setDate(new DateTime());

        $paxb = PAXB::createMarshaller();
        $xml  = $paxb->marshal($book);

        $this->assertNotNull($xml);
    }

}