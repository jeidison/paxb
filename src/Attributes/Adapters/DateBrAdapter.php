<?php

namespace Jeidison\PAXB\Attributes\Adapters;

class DateBrAdapter implements XmlAdapter
{

    public function marshal(object $object): string
    {
        return $object->format('d/m/Y');
    }

    public function unmarshal(object $object): object
    {

    }
}