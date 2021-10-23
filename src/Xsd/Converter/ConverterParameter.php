<?php

namespace Jeidison\PAXB\Xsd\Converter;

class ConverterParameter
{
    public string $namespace;
    public array $complexTypes;
    public array $simpleTypes;
    public bool $withGetters;
    public bool $withSetters;
}