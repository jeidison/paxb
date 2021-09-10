<?php

namespace Jeidison\PAXB\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
class XmlAttribute
{
    public function __construct(
        public ?string $name = null,
        public ?string $namespace = null,
        public bool $required = false,
    ) {}
}