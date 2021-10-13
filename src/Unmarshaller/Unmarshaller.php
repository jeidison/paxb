<?php

namespace Jeidison\PAXB\Unmarshaller;

use Jeidison\PAXB\Attributes\Adapters\XmlAdapter;
use Jeidison\PAXB\Attributes\Adapters\XmlPhpTypeAdapter;
use Jeidison\PAXB\Attributes\Element;
use Jeidison\PAXB\Attributes\XmlAttribute;
use Jeidison\PAXB\Attributes\XmlElement;
use Jeidison\PAXB\Commons\MarshallerCommons;
use Jeidison\PAXB\Exception\MarshalException;
use Jeidison\PAXB\Exception\UnmarshalException;
use ReflectionObject;
use ReflectionProperty;
use stdClass;
use SimpleXMLElement;
use ReflectionClass;

class Unmarshaller implements IUnmarshaller
{
    use MarshallerCommons;


    public function __construct(private string $typeObject = stdClass::class)
    {}

    public function setTypeObject(string $typeObject)
    {
        if (class_exists($typeObject))
            $this->typeObject = $typeObject;
    }

    public function unmarshal(string $xml): object
    {
        $object    = new $this->typeObject;
        $objectXml = $this->xmlToObject($xml);

        $xmlRootElement         = $objectXml->getName();
        $reflectionObject       = new ReflectionObject($object);
        $attributes             = $reflectionObject->getAttributes();
        $xmlRootElementInObject = $this->retrieveRootElement($attributes, $reflectionObject->getShortName());

        if ($xmlRootElementInObject !== $xmlRootElement)
            throw new UnmarshalException("'XmlRootElement' da classe é diferente da tag root do XML.");

        foreach ($reflectionObject->getProperties() as $reflectionProperty) {
            $reflectionProperty->setAccessible(true);
            if ($this->isAttribute($reflectionProperty)) {
                $value = $this->getAttributeValue($objectXml, $reflectionProperty);
                if ($value != null)
                    $value = $this->normalizeValueProperty($reflectionProperty, $value);
            } else {
                $value = $this->getPropertyValue($objectXml, $reflectionProperty);
            }

            if ($value != null)
                $reflectionProperty->setValue($object, $value);
        }

        return $object;
    }

    private function getPropertyValue(SimpleXMLElement $objectXml, ReflectionProperty $reflectionProperty): string|null|object
    {
        $tagName = $this->getTagName($reflectionProperty);
        if (!property_exists($objectXml, $tagName))
            return null;

        $valueTag      = $objectXml->$tagName;
        $isObjectChild = $this->isObjectChild($reflectionProperty, $valueTag);
        if (!$isObjectChild)
            return $valueTag;

        $typeChild   = $reflectionProperty->getType()->getName();
        $objectChild = new $typeChild;

        $reflectionObject = new ReflectionObject($objectChild);
        foreach ($reflectionObject->getProperties() as $property) {
            $property->setAccessible(true);

            if ($this->isAttribute($property)) {
                $value = $this->getAttributeValue($valueTag, $property);
            } else {
                $value = $this->getPropertyValue($valueTag, $property);
            }

            if ($value != null)
                $value = $this->normalizeValueProperty($property, $value);

            if ($value != null)
                $property->setValue($objectChild, $value);
        }

        return $objectChild;
    }

    private function normalizeValueProperty(ReflectionProperty $reflectionProperty, mixed $value): string|object|null
    {
        if ($value instanceof SimpleXMLElement)
            $value = (string)$value;

        $attributes = $reflectionProperty->getAttributes();
        if (count($attributes) <= 0)
            return $value;

        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            if (!$attributeInstance instanceof XmlPhpTypeAdapter)
                continue;

            $adapterName = $attributeInstance->adapter;
            $adapter     = new $adapterName;
            if (!$adapter instanceof XmlAdapter)
                throw new UnmarshalException("Adapter não é do tipo 'XmlAdapter'");

            return $adapter->unmarshal($value);
        }

        return $value;
    }

    private function getAttributeValue(SimpleXMLElement $objectXml, ReflectionProperty $reflectionProperty): ?string
    {
        $attributeXmlName = $this->getAttributeXmlName($reflectionProperty);
        $attributesXml    = json_decode(json_encode($objectXml->attributes()));

        if (!property_exists($attributesXml, '@attributes'))
            return null;

        $attributesXml = $attributesXml->{'@attributes'};
        if (!property_exists($attributesXml, $attributeXmlName))
            return null;

        return $attributesXml->$attributeXmlName;
    }

    private function xmlToObject(string $xml): SimpleXMLElement
    {
        return simplexml_load_string($xml);
    }

    private function isObjectChild(ReflectionProperty $reflectionProperty, object $objectInstance): bool
    {
        $reflectionType = $reflectionProperty->getType();
        if ($reflectionType === null)
            return false;

        if ($reflectionType->isBuiltin())
            return false;

        $reflectionClass = new ReflectionClass($reflectionType->getName());
        if ($reflectionClass->isInternal())
            return false;

        if (!$reflectionClass->isInstantiable())
            return false;

        return true;
    }

}
