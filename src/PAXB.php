<?php

namespace PhpXml\PAXB;

use PhpXml\PAXB\Marshaller\Marshaller;

class PAXB
{

    /*Transforma XML em Objeto*/
    public function unmarshal(string $xml): object
    {

    }

    public static function createMarshaller(): Marshaller
    {
        return new Marshaller();
    }
}