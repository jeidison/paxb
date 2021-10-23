<?php

namespace Jeidison\PAXB\Xsd\ClassBuilder;

use PhpParser\Node\Arg;
use PhpParser\Node\Scalar\String_;

class AttributeTemplate
{
    private string $type;
    private ?string $argumentName = null;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getArgumentName(): ?string
    {
        return $this->argumentName;
    }

    public function getArgumentNameAsArrayArg(): array
    {
        if ($this->getArgumentName() == null)
            return [];

        return [new Arg(
            new String_($this->getArgumentName())
        )];
    }

    public function setArgumentName(string $argumentName): AttributeTemplate
    {
        $this->argumentName = $argumentName;
        return $this;
    }

    public function getName(): string
    {
        $itens = explode('\\', $this->getType());
        return $itens[count($itens) -1];
    }

}