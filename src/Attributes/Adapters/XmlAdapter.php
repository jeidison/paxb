<?php

namespace Jeidison\PAXB\Attributes\Adapters;

interface XmlAdapter
{
    public function marshal(mixed $object): mixed;
    public function unmarshal(mixed $object): mixed;
}