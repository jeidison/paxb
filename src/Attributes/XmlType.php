<?php

namespace Jeidison\PAXB\Attributes;

use Attribute;

#[Attribute]
class XmlType
{
    public function __construct(public array $propOrder)
    {}
}