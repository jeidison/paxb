<?php

namespace PhpXml\PAXB\Attributes\Adapters;

interface XmlAdapter
{
    public function marshal(object $object): ?string;
    public function unmarshal(object $object): ?object;
}