<?php

namespace PhpXml\PAXB\Attributes\Adapters;

class DateTimeBrAdapter implements XmlAdapter
{

    public function marshal(object $object): string
    {
        return $object->format('d/m/Y H:i:s');
    }

    public function unmarshal(object $object): object
    {

    }
}