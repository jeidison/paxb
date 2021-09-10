<?php

namespace PhpXml\PAXB\Marshaller;

interface IMarshaller
{
    public function marshal(object $paxbObject): string;
}