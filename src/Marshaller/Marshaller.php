<?php

namespace Jeidison\PAXB\Marshaller;

use DOMDocument;
use DOMNode;
use Jeidison\PAXB\Attributes\Adapters\XmlAdapter;
use Jeidison\PAXB\Attributes\Adapters\XmlPhpTypeAdapter;
use Jeidison\PAXB\Attributes\Element;
use Jeidison\PAXB\Attributes\XmlAttribute;
use Jeidison\PAXB\Attributes\XmlRootElement;
use Jeidison\PAXB\Attributes\XmlTransient;
use Jeidison\PAXB\Attributes\XmlType;
use Jeidison\PAXB\Commons\MarshallerCommons;
use Jeidison\PAXB\Exception\MarshalException;
use ReflectionAttribute;
use ReflectionObject;
use ReflectionProperty;

/**
 * @author Jeidison Farias <jeidison.farias@gmail.com>
 */
class Marshaller implements IMarshaller
{
    use MarshallerCommons;

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
}