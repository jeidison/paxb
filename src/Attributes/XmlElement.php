<?php

namespace PhpXml\PAXB\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::TARGET_PARAMETER)]
class XmlElement extends Element
{
    public function __construct(
        public ?string $name = null,
        public ?string $namespace = null,
        public ?string $defaultValue = null,
        public bool $nillable = false,
        public bool $required = false,
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }
}