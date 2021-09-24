<?php

namespace Jeidison\PAXB\Attributes\Adapters;

use DateTime;

class DateBrAdapter implements XmlAdapter
{

    public function marshal(mixed $object): mixed
    {
        return $object->format('d/m/Y');
    }

    public function unmarshal(mixed $object): mixed
    {
        return DateTime::createFromFormat('d/m/Y', $object)
                       ->setTime(null, null, null);
    }
}