<?php

namespace Jeidison\PAXB\Xsd;

class Xsd2PhpParameter
{
    public string $namespace;
    public bool $withGetters;
    public bool $withSetters;
    public string $pathRootXsd;
    public string $pathStoreClasses;
}