<?php

namespace Jeidison\PAXB\Xsd\ClassBuilder;

class PropertyTemplate
{
    private string $name;
    private string $accessType = 'private'; // private, public, protected
    private string $type;
    /**@var array<AttributeTemplate>*/
    private array $attributes = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): PropertyTemplate
    {
        $this->name = $name;
        return $this;
    }

    public function getAccessType(): string
    {
        return $this->accessType;
    }

    public function setAccessType(string $accessType): PropertyTemplate
    {
        $this->accessType = $accessType;
        return $this;
    }

    public function getType(): string
    {
        return empty($this->type) ? 'mixed' : $this->type;
    }

    public function setType(string $type): PropertyTemplate
    {
        $this->type = $type;
        return $this;
    }

    public function withAttributes(array $attributes): self
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function addAttribute($attribute): self
    {
        $this->attributes[] = $attribute;
        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

}