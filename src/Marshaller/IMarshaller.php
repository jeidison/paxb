<?php

namespace Jeidison\PAXB\Marshaller;

interface IMarshaller
{
    public function marshal(object $paxbObject): string;
}