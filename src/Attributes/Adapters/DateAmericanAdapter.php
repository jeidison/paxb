<?php

namespace Jeidison\PAXB\Attributes\Adapters;

class DateAmericanAdapter implements XmlAdapter
{

    public function marshal(mixed $object): ?string
    {
        if ($object === null)
            return null;

        return $object->format('Y-m-d');
    }

    public function unmarshal(mixed $object): ?object
    {

    }
}