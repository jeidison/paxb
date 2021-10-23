<?php

namespace Jeidison\PAXB\Xsd\Converter;

use Generator;
use Jeidison\PAXB\Attributes\XmlAttribute;
use Jeidison\PAXB\Attributes\XmlElement;
use Jeidison\PAXB\Attributes\XmlRootElement;
use Jeidison\PAXB\Xsd\ClassBuilder\AttributeTemplate;
use Jeidison\PAXB\Xsd\ClassBuilder\ClassBuilder;
use Jeidison\PAXB\Xsd\ClassBuilder\PropertyTemplate;

class ComplexTypeClassConverter
{
    public function convert(ConverterParameter $parameter): Generator
    {
        foreach ($parameter->complexTypes as $complexType) {
            $className  = ucwords($complexType->rootName ?? $complexType->type);
            $properties = $this->buildProperties($complexType);

            $classBuilder = new ClassBuilder();
            $classBuilder->withClassName($className);
            $classBuilder->withNamespace($parameter->namespace);
            $classBuilder->withGetters($parameter->withGetters);
            $classBuilder->withSetters($parameter->withSetters);
            $classBuilder->withProperties($properties);

            if (isset($complexType->rootName)) {
                $attribute = $this->buildAttribute($complexType, isXmlRootAttribute: true);
                $classBuilder->addAttribute($attribute);
            }

            yield [$className, $classBuilder->build()];
        }
    }

    private function buildProperties(object $complexType): array
    {
        $properties = [];
        foreach ($complexType->xmlAttributes ?? [] as $xmlAttribute) {
            $properties[] = $this->buildProperty($xmlAttribute, true);
        }

        foreach ($complexType->xmlElements ?? [] as $xmlElement) {
            $properties[] = $this->buildProperty($xmlElement);
        }

        return $properties;
    }

    private function buildProperty(object $xmlElement, bool $isXmlAttribute = false): PropertyTemplate
    {
        $attribute = $this->buildAttribute($xmlElement, $isXmlAttribute);
        $name = $xmlElement->name;
        if ($name != strtoupper($name))
            $name = lcfirst($xmlElement->name);

        $propertyTemplate = new PropertyTemplate();
        $propertyTemplate->setType($xmlElement->type);
        $propertyTemplate->setName($name);
        $propertyTemplate->addAttribute($attribute);

        return $propertyTemplate;
    }

    private function buildAttribute(object $xmlElement, bool $isXmlAttribute = false, $isXmlRootAttribute = false): AttributeTemplate
    {
        $type = XmlElement::class;
        if ($isXmlAttribute)
            $type = XmlAttribute::class;
        elseif ($isXmlRootAttribute)
            $type = XmlRootElement::class;

        $attributeTemplate = new AttributeTemplate;
        $attributeTemplate->setType($type);
        $attributeTemplate->setArgumentName($xmlElement->name ?? $xmlElement->rootName);

        return $attributeTemplate;
    }

}