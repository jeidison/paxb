<?php

namespace PhpXml\PAXB\Attributes\Adapters;

class DateAmericanAdapter implements XmlAdapter
{

    public function marshal(?object $object): ?string
    {
        if ($object === null)
            return null;

        return $object->format('Y-m-d');
    }

    public function unmarshal(object $object): ?object
    {

    }
}