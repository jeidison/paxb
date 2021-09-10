<?php

namespace PhpXml\PAXB\Marshaller;

use DOMDocument;
use DOMNode;
use PhpXml\PAXB\Attributes\Adapters\XmlAdapter;
use PhpXml\PAXB\Attributes\Adapters\XmlPhpTypeAdapter;
use PhpXml\PAXB\Attributes\Element;
use PhpXml\PAXB\Attributes\XmlAttribute;
use PhpXml\PAXB\Attributes\XmlRootElement;
use PhpXml\PAXB\Attributes\XmlTransient;
use PhpXml\PAXB\Attributes\XmlType;
use PhpXml\PAXB\Exception\MarshalException;
use ReflectionAttribute;
use ReflectionObject;
use ReflectionProperty;

/**
 * @author Jeidison Farias <p@tchwork.com>
 */
class Marshaller implements IMarshaller
{
    private string $version = '1.0';
    private string $encoding = 'UTF-8';
    private ?bool $xmlStandalone = null;
    private ?string $documentURI = null;
    private bool $formatOutput = false;
    private ?bool $preserveWhiteSpace = null;
    private ?string $prefix = null;
    private ?string $namespaceURI = null;
    private ?bool $validateOnParse = null;

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function setEncoding(string $encoding): void
    {
        $this->encoding = $encoding;
    }

    public function setXmlStandalone(?bool $xmlStandalone): void
    {
        $this->xmlStandalone = $xmlStandalone;
    }

    public function setDocumentURI(?string $documentURI): void
    {
        $this->documentURI = $documentURI;
    }

    public function setFormatOutput(bool $formatOutput): void
    {
        $this->formatOutput = $formatOutput;
    }

    public function setPrefix(?string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function setNamespaceURI(?string $namespaceURI): void
    {
        $this->namespaceURI = $namespaceURI;
    }

    public function setPreserveWhiteSpace(?bool $preserveWhiteSpace): void
    {
        $this->preserveWhiteSpace = $preserveWhiteSpace;
    }

    public function setValidateOnParse(?bool $validateOnParse): void
    {
        $this->validateOnParse = $validateOnParse;
    }

    protected function buildDom(): DOMDocument
    {
        $dom = new DOMDocument($this->version, $this->encoding);

        if ($this->formatOutput !== null)
            $dom->formatOutput = $this->formatOutput;

        if ($this->prefix !== null)
            $dom->prefix = $this->prefix;

        if ($this->documentURI !== null)
            $dom->documentURI = $this->documentURI;

        if ($this->namespaceURI !== null)
            $dom->namespaceURI = $this->namespaceURI;

        if ($this->xmlStandalone !== null)
            $dom->xmlStandalone = $this->xmlStandalone;

        if ($this->preserveWhiteSpace !== null)
            $dom->preserveWhiteSpace = $this->preserveWhiteSpace;

        if ($this->validateOnParse !== null)
            $dom->validateOnParse = $this->validateOnParse;

        return $dom;
    }

    public function marshal(object $paxbObject): string
    {
        $reflectionObject = new ReflectionObject($paxbObject);
        $attributesRoot   = $reflectionObject->getAttributes();
        $rootElement      = $this->retrieveRootElement($attributesRoot, $reflectionObject->getShortName());
        $rootNamespace    = $this->retrieveNamespaceRootElement($attributesRoot);

        $dom = $this->buildDom();
        if ($rootNamespace !== null) {
            $root = $dom->createElementNS($rootNamespace, $rootElement);
        } else {
            $root = $dom->createElement($rootElement);
        }
        $root = $this->reflectionProperties($reflectionObject, $paxbObject, $dom, $root);

        $dom->appendChild($root);

        return $dom->saveXML();
    }

    private function marshallChild(ReflectionProperty $reflectionProperty, object $objectInstance, DOMDocument $dom): ?DOMNode
    {
        $tagName = $this->getTagName($reflectionProperty);
        $root    = $dom->createElement($tagName);

        $paxbObject       = $this->getTagValue($reflectionProperty, $objectInstance);
        $reflectionObject = new ReflectionObject($paxbObject);

        return $this->reflectionProperties($reflectionObject, $paxbObject, $dom, $root);
    }

    private function reflectionProperties(ReflectionObject $reflectionObject, object $paxbObject, DOMDocument $dom, DOMNode $root): DOMNode
    {
        $reflectionProperties = $reflectionObject->getProperties();
        $hasXmlType           = $this->hasXmlType($reflectionObject);
        if ($hasXmlType) {
            $propOrders           = $this->getXmlTypePropOrder($reflectionObject);
            $reflectionProperties = $this->sortReflectionProperty($reflectionProperties, $propOrders);
        }

        foreach ($reflectionProperties as $reflectionProperty) {
            $reflectionProperty->setAccessible(true);

            if ($this->isTransient($reflectionProperty))
                continue;

            $isChild = $this->isChild($reflectionProperty, $paxbObject);
            if ($isChild) {
                $child = $this->marshallChild($reflectionProperty, $paxbObject, $dom);
                if ($child !== null)
                    $root->appendChild($child);

                continue;
            }

            $tagValue = $this->getTagValue($reflectionProperty, $paxbObject);
            if (empty($tagValue))
                continue;

            if ($this->isAttribute($reflectionProperty)) {
                $attributeXmlName = $this->getAttributeXmlName($reflectionProperty);
                $root->setAttribute($attributeXmlName, $tagValue);
            } else {
                $tagName = $this->getTagName($reflectionProperty);
                $child   = $dom->createElement($tagName, $tagValue);
                $root->appendChild($child);
            }
        }

        return $root;
    }

    private function getTagValue(ReflectionProperty $reflectionProperty, object $objectInstance): mixed
    {
        $value      = $reflectionProperty->getValue($objectInstance);
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
                throw new MarshalException("Adapter is not an instance of 'XmlAdapter'");

            return $adapter->marshal($value);
        }

        return $value;
    }

    private function getAttributeXmlName(ReflectionProperty $reflectionProperty): string
    {
        $attributes = $reflectionProperty->getAttributes();
        if (count($attributes) == 0)
            return $this->normalizeTagName($reflectionProperty->getName());

        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            if ($attributeInstance instanceof XmlAttribute)
                return $attributeInstance->name ?? $this->normalizeTagName($reflectionProperty->getName());
        }

        return $this->normalizeTagName($reflectionProperty->getName());
    }

    private function isChild(ReflectionProperty $reflectionProperty, object $objectInstance): bool
    {
        $reflectionType = $reflectionProperty->getType();
        if ($reflectionType === null)
            return false;

        if ($reflectionType->isBuiltin())
            return false;

        $value = $reflectionProperty->getValue($objectInstance);
        if ($value === null)
            return false;

        $reflectionObject = new ReflectionObject($value);

        if ($reflectionObject->isInternal())
            return false;

        if (!$reflectionObject->isInstantiable())
            return false;

        return true;
    }

    private function getXmlTypePropOrder(ReflectionObject $reflectionObject): array
    {
        $attributes = $reflectionObject->getAttributes();
        if (count($attributes) <= 0)
            return [];

        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            if ($attributeInstance instanceof XmlType)
                return $attributeInstance->propOrder;
        }

        return [];
    }

    private function hasXmlType(ReflectionObject $reflectionObject): bool
    {
        $attributes = $reflectionObject->getAttributes();
        if (count($attributes) <= 0)
            return false;

        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            if ($attributeInstance instanceof XmlType)
                return true;
        }

        return false;
    }

    private function isTransient(ReflectionProperty $reflectionProperty): bool
    {
        $attributes = $reflectionProperty->getAttributes();
        if (count($attributes) <= 0)
            return false;

        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            if ($attributeInstance instanceof XmlTransient)
                return true;
        }

        return false;
    }

    private function isAttribute(ReflectionProperty $reflectionProperty): bool
    {
        $attributes = $reflectionProperty->getAttributes();
        if (count($attributes) <= 0)
            return false;

        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            if ($attributeInstance instanceof XmlAttribute)
                return true;
        }

        return false;
    }

    private function getTagName(ReflectionProperty $reflectionProperty): string
    {
        $attributes = $reflectionProperty->getAttributes();
        if (count($attributes) <= 0)
            return $this->normalizeTagName($reflectionProperty->getName());

        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            if ($attributeInstance instanceof Element)
                return $attributeInstance->getName() ?? $this->normalizeTagName($reflectionProperty->getName());
        }

        return $this->normalizeTagName($reflectionProperty->getName());
    }

    /**
     * @param array<ReflectionAttribute> $attributes
     */
    private function retrieveRootElement(array $attributes, string $className): string
    {
        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            if ($attributeInstance instanceof XmlRootElement)
                return $attributeInstance->getName() ?? $this->normalizeTagName($className);
        }

        return $this->normalizeTagName($className);
    }

    /**
     * @param array<ReflectionAttribute> $attributes
     */
    private function retrieveNamespaceRootElement(array $attributes): ?string
    {
        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            if ($attributeInstance instanceof XmlRootElement)
                return $attributeInstance->namespace;
        }

        return null;
    }

    /**
     * @param array<ReflectionProperty> $properties
     */
    private function sortReflectionProperty(array $properties, array $sortBy): array
    {
        if (count($sortBy) != count($properties))
            throw new MarshalException("'propOrder' deve conter todos os campos do XML.");

        $ordered = array();
        foreach($sortBy as $key) {
            foreach ($properties as $propertyIndex => $property) {
                if($key == $this->getTagName($property)) {
                    $ordered[$key] = $property;
                    unset($properties[$propertyIndex]);
                }
            }
        }

        return $ordered;
    }

    private function normalizeTagName(string $tagName): string
    {
        return lcfirst(ucwords($tagName));
    }
}