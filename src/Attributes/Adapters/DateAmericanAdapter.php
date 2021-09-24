<?php

namespace Jeidison\PAXB\Attributes\Adapters;

class DateAmericanAdapter implements XmlAdapter
{

    public function marshal(mixed $object): mixed
    {
        if ($object === null)
            return null;

        return $object->format('Y-m-d');
    }

    public function unmarshal(mixed $object): mixed
    {
        return null;
    }
}