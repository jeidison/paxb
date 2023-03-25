<?php

namespace Jeidison\PAXB\Xsd;

abstract class PrimitiveTypes
{
    const COMPLETE_LIST = ['int', 'bool', 'string', 'float', 'array', 'object'];

    public static function hasCast(string $type): ?string
    {
        $fromTo = [
            'base64Binary' => 'string',
            'anyURI' => 'string',
        ];

        return $fromTo[$type] ?? null;
    }
}