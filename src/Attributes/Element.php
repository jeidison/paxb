<?php

namespace Jeidison\PAXB\Attributes;

abstract class Element
{
    abstract public function getName(): ?string;
}