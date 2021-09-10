<?php

namespace PhpXml\PAXB;

interface Unmarshaller
{
    public function unmarshal(string $xml): object;
}