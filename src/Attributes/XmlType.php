<?php

namespace PhpXml\PAXB\Attributes;

use Attribute;

#[Attribute]
class XmlType
{
    public function __construct(public array $propOrder)
    {}
}