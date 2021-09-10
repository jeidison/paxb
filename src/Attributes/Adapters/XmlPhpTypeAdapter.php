<?php

namespace PhpXml\PAXB\Attributes\Adapters;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::TARGET_PARAMETER)]
class XmlPhpTypeAdapter
{
    public function __construct(public string $adapter)
    {}
}