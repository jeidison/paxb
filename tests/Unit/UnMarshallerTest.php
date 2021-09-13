<?php

namespace Jeidison\Tests\Unit;

use Jeidison\PAXB\PAXB;
use Jeidison\Tests\Resources\Beans\Book;
use Jeidison\Tests\TestCase;

class UnMarshallerTest extends TestCase
{
    public function testUnmarshal()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<livros identificador="1">
    <nome>PHP XML Binding</nome>
    <date>10/09/2021</date>
    <author_data>
        <name>Jeidison Farias</name>
        <email>jeidison.farias@gmail.com</email>
        <address>
            <number>123</number>
            <street>Rua 10</street>
        </address>
    </author_data>
</livros>';

        $unmarshaller = PAXB::createUnmarshal(Book::class);
        $book = $unmarshaller->unmarshal($xml);

        $this->assertNotNull($book);
    }
}