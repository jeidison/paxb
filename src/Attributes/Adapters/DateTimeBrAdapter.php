<?php

namespace Jeidison\PAXB\Attributes\Adapters;

class DateTimeBrAdapter implements XmlAdapter
{

    public function marshal(mixed $object): string
    {
        return $object->format('d/m/Y H:i:s');
    }

    public function unmarshal(mixed $object): object
    {

    }
}