<?php

namespace Jeidison\PAXB\Attributes\Adapters;

interface XmlAdapter
{
    public function marshal(?object $object): ?string;
    public function unmarshal(?string $object): ?object;
}