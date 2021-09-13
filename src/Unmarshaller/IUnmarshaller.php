<?php

namespace Jeidison\PAXB\Unmarshaller;

interface IUnmarshaller
{
    public function unmarshal(string $xml): object;
}