<?php

namespace Jeidison\PAXB;

interface Unmarshaller
{
    public function unmarshal(string $xml): object;
}