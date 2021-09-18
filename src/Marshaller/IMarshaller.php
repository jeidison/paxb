<?php

namespace Jeidison\PAXB\Marshaller;

use DOMDocument;

interface IMarshaller
{
    public function marshal(object $paxbObject): string|DOMDocument;
}