<?php

namespace Jeidison\PAXB;

use Jeidison\PAXB\Marshaller\Marshaller;
use Jeidison\PAXB\Unmarshaller\Unmarshaller;

class PAXB
{
    public static function createUnmarshal(?string $typeObject = null): Unmarshaller
    {
        $unmarshaller = new Unmarshaller();
        if (class_exists($typeObject))
            $unmarshaller->setTypeObject($typeObject);

        return $unmarshaller;
    }

    public static function createMarshaller(): Marshaller
    {
        return new Marshaller();
    }
}