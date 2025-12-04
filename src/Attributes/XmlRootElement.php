<?php

namespace Jeidison\PAXB\Attributes;

use Attribute;

#[Attribute]
class XmlRootElement extends Element
{
    /**
     * @param string|null $name Nome do elemento raiz
     * @param string|null $namespace URI do namespace
     * @param string|null $namespacePrefix Prefixo do namespace (ex: 'ans', 'ansTISS')
     * @param array<string, string> $namespaceDeclarations Declarações de namespace (ex: ['ans' => 'http://...', 'xsi' => 'http://www.w3.org/2001/XMLSchema-instance'])
     * @param array<string, string> $rootAttributes Atributos adicionais no root (ex: ['xsi:schemaLocation' => '...'])
     */
    public function __construct(
        public ?string $name = null,
        public ?string $namespace = null,
        public ?string $namespacePrefix = null,
        public array $namespaceDeclarations = [],
        public array $rootAttributes = [],
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }
}
