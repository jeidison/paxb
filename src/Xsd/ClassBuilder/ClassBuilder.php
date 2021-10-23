<?php

namespace Jeidison\PAXB\Xsd\ClassBuilder;

use Jeidison\PAXB\Attributes\Adapters\XmlPhpTypeAdapter;
use Jeidison\PAXB\Attributes\XmlAttribute;
use Jeidison\PAXB\Attributes\XmlElement;
use Jeidison\PAXB\Attributes\XmlRootElement;
use Jeidison\PAXB\Attributes\XmlTransient;
use Jeidison\PAXB\Attributes\XmlType;
use Jeidison\PAXB\Attributes\XmlValue;
use Jeidison\PAXB\Exception\Xsd2PhpException;
use Jeidison\PAXB\Xsd\PrimitiveTypes;
use PhpParser\Builder\Method;
use PhpParser\Builder\Namespace_;
use PhpParser\Builder\Property;
use PhpParser\BuilderFactory;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Return_;
use PhpParser\PrettyPrinter\Standard;

class ClassBuilder
{
    private string $namespace;
    private string $className;
    private bool $hasGetters = true;
    private bool $hasSetters = true;
    /**@var array<PropertyTemplate>*/
    private array $properties = [];
    /**@var array<AttributeTemplate>*/
    private array $attributes = [];

    //
    private array $uses = [];

    public function withNamespace(string $namespace): self
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function withClassName(string $className): self
    {
        $this->className = $className;
        return $this;
    }

    public function withProperties(array $properties): self
    {
        $this->properties = $properties;
        return $this;
    }

    public function addProperty($property): self
    {
        $this->properties[] = $property;
        return $this;
    }

    public function withGetters($hasGetters): self
    {
        $this->hasGetters = $hasGetters;
        return $this;
    }

    public function withSetters(bool $hasSetters): self
    {
        $this->hasSetters = $hasSetters;
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

    public function build(): string
    {
        if ($this->namespace === null)
            throw new Xsd2PhpException("Namespace é obrigatório.");

        if ($this->className === null)
            throw new Xsd2PhpException("Nome da classe é obrigatório.");

        $factory          = new BuilderFactory;
        $fileClassBuilder = $factory->namespace($this->namespace);
        $classBuilder     = $factory->class($this->className);

        foreach ($this->getAttributes() as $attributeTemplate) {
            $classBuilder->addAttribute(
                new Attribute(
                    new Name($attributeTemplate->getName()),
                    $attributeTemplate->getArgumentNameAsArrayArg()
                )
            );

            $this->addUseClass($attributeTemplate, $fileClassBuilder, $factory);
        }

        foreach ($this->properties as $property) {
            $propertyBuilder = $this->buildProperty($factory, $property, $fileClassBuilder);
            $classBuilder->addStmt($propertyBuilder);

            if ($this->hasGetters) {
                $builderMethod = $this->buildGetter($factory, $property);
                $classBuilder->addStmt($builderMethod);
            }

            if ($this->hasSetters) {
                $builderMethod = $this->buildSetter($factory, $property);
                $classBuilder->addStmt($builderMethod);
            }
        }

        $fileClassBuilder->addStmt($classBuilder);

        $prettyPrinter = new Standard();
        return $prettyPrinter->prettyPrintFile([$fileClassBuilder->getNode()]);
    }

    private function buildProperty(BuilderFactory $factory, PropertyTemplate $property, Namespace_ $fileClassBuilder): Property
    {
        $propertyBuilder = $factory->property($property->getName());
        $typeProperty    = $this->getNameTypeProperty($property->getType());
        $propertyBuilder->setType($typeProperty);

        if (!in_array($property->getType(), PrimitiveTypes::COMPLETE_LIST))
            $this->addUseClass($property, $fileClassBuilder, $factory);

        if ($property->getAccessType() === 'private') {
            $propertyBuilder->makePrivate();
        } elseif ($property->getAccessType() === 'protected') {
            $propertyBuilder->makeProtected();
        } elseif ($property->getAccessType() === 'public') {
            $propertyBuilder->makePublic();
        }

        foreach ($property->getAttributes() as $attributeTemplate) {
            $propertyBuilder->addAttribute(
                new Attribute(
                    new Name($attributeTemplate->getName()),
                    $attributeTemplate->getArgumentNameAsArrayArg()
                )
            );

            $this->addUseClass($attributeTemplate, $fileClassBuilder, $factory);
        }

        return $propertyBuilder;
    }

    private function buildGetter(BuilderFactory $factory, PropertyTemplate $property): Method
    {
        $methodGetterName = 'get'.ucwords($property->getName());
        return $factory->method($methodGetterName)
            ->makePublic()
            ->setReturnType($this->getNameTypeProperty($property->getType()))
            ->addStmt(new Return_(
                    new PropertyFetch(
                        new Variable('this'),
                        $property->getName()
                    )
                )
            );
    }

    private function buildSetter(BuilderFactory $factory, PropertyTemplate $property): Method
    {
        $methodGetterName = 'set'.ucwords($property->getName());
        return $factory->method($methodGetterName)
            ->makePublic()
            ->setReturnType('void')
            ->addParam(
                $factory->param($property->getName())
                        ->setType($this->getNameTypeProperty($property->getType()))
            )
            ->addStmt(
                new Assign(
                    new PropertyFetch(new Variable('this'), $property->getName()),
                    new Variable($property->getName())
                )
            );
    }

    private function addUseClass(AttributeTemplate|PropertyTemplate $attributeTemplate, Namespace_ $fileClassBuilder, BuilderFactory $factory): void
    {
        $usesAccepted = [\DateTime::class, XmlElement::class, XmlRootElement::class, XmlAttribute::class, XmlTransient::class, XmlType::class, XmlValue::class, XmlPhpTypeAdapter::class];
        if (!in_array($attributeTemplate->getType(), $usesAccepted))
            return;

        if (!isset($this->uses[$attributeTemplate->getType()])) {
            $fileClassBuilder->addStmt($factory->use($attributeTemplate->getType()));
            $this->uses[$attributeTemplate->getType()] = $attributeTemplate->getType();
        }
    }

    private function getNameTypeProperty(string $typeProperty): string
    {
        if (str_contains($typeProperty, '\\')) {
            $typePropertyExploded = explode('\\', $typeProperty);
            $typeProperty = $typePropertyExploded[count($typePropertyExploded) - 1];
        }

        return $typeProperty;
    }
}