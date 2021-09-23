<?php

namespace Jeidison\PAXB\Unmarshaller;

use DOMDocument;
use DOMElement;
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

    protected function buildDom(): DOMDocument
    {
        return new DOMDocument();
    }

    public function unmarshal(string $xml): object
    {
        $object = new $this->typeObject;

        $dom = $this->buildDom();
        $dom->loadXML($xml);

        $elementRoot    = $dom->documentElement;
        $xmlRootElement = $elementRoot->nodeName;

        $reflectionObject = new ReflectionObject($object);
        $attributes       = $reflectionObject->getAttributes();

        $xmlRootElementInObject = $this->retrieveRootElement($attributes, $reflectionObject->getShortName());

        if ($xmlRootElementInObject !== $xmlRootElement)
            throw new UnmarshalException("XmlRootElement da classe é diferente da tag root do XML.");

        $reflectionProperties = $reflectionObject->getProperties();
        foreach ($reflectionProperties as $reflectionProperty) {
            $reflectionProperty->setAccessible(true);

            if ($this->isAttribute($reflectionProperty)) {
                $attributeXmlName = $this->getAttributeXmlName($reflectionProperty);
                $value = $this->getAttributeValue($dom, $xmlRootElement, $attributeXmlName);
            } else {
                $tagName = $this->getTagName($reflectionProperty);
                $value = $this->getPropertyValue($reflectionProperty, $dom, $tagName);
            }

            $reflectionProperty->setValue($object, $value);
        }

        return $object;
    }

    private function getAttributeValue(DOMDocument $dom, string $elementName, string $attributeXmlName): ?string
    {
        $xmlElements = $dom->getElementsByTagName($elementName);
        foreach ($xmlElements as $xmlElement) {
            foreach ($xmlElement->attributes as $attribute) {
                if ($attribute->nodeName === $attributeXmlName) {
                    return $attribute->value;
                }
            }
        }

        return null;
    }

    private function getPropertyValue(ReflectionProperty $reflectionProperty, DOMDocument $dom, string $elementName): string|null|object
    {
        $xmlElements = $dom->getElementsByTagName($elementName);
        foreach ($xmlElements as $xmlElement) {
            if ($xmlElement->nodeName !== $elementName)
                continue;

            if ($xmlElement->childNodes->count() > 1)
                return $this->unmarshallChild($reflectionProperty, $dom, $xmlElement);

            if ($xmlElement->hasChildNodes()) {
                $value = $xmlElement->childNodes[0]->nodeValue;
            } else {
                $value = $xmlElement->nodeValue;
            }

            if ($value === null)
                return null;

            return $this->normalizeValueProperty($reflectionProperty, $value);
        }

        return null;
    }

    private function unmarshallChild(ReflectionProperty $reflectionProperty, DOMDocument $dom, DOMElement $xmlElement)
    {
        $type = $reflectionProperty->getType();
        $object = new ($type->getName());

        foreach ($xmlElement->childNodes as $childNode) {
            if (empty(trim($childNode->nodeValue)))
                continue;

            $reflectionObject = new ReflectionObject($object);
            $propertyName = $this->getPropertyName($reflectionObject, $childNode->nodeName);

            $newReflectionProperty = $reflectionObject->getProperty($propertyName);
            $newReflectionProperty->setAccessible(true);

            $value = $this->getPropertyValue($newReflectionProperty, $dom, $childNode->nodeName);

            $newReflectionProperty->setValue($object, $value);
        }

        return $object;
    }

    private function normalizeValueProperty(ReflectionProperty $reflectionProperty, string $value): string|object
    {
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

    private function getPropertyName(ReflectionObject $reflectionObject, string $elementName): string
    {
        foreach ($reflectionObject->getProperties() as $reflectionProperty) {
            $reflectionProperty->setAccessible(true);
            foreach($reflectionProperty->getAttributes() as $reflectionAttribute) {
                $attribute = $reflectionAttribute->newInstance();
                if(!$attribute instanceof Element)
                    continue;

                if ($elementName === $attribute->getName())
                    return $reflectionProperty->getName();
            }
        }

        return $elementName;
    }
}