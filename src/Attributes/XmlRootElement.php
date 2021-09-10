<?php

namespace Jeidison\PAXB\Attributes;

use Attribute;

#[Attribute]
class XmlRootElement extends Element
{
    public function __construct(
        public ?string $name = null,
        public ?string $namespace = null,
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }
}