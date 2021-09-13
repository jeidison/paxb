<?php

namespace Jeidison\PAXB\Attributes\Adapters;

use DateTime;

class DateBrAdapter implements XmlAdapter
{

    public function marshal(?object $object): string
    {
        return $object->format('d/m/Y');
    }

    public function unmarshal(?string $object): ?object
    {
        return DateTime::createFromFormat('d/m/Y', $object)
                       ->setTime(null, null, null);
    }
}