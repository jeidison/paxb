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
use Jeidison\PAXB\Attributes\XmlValue;
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
    private bool $responseAsString = true;

    public function setResponseAsString(bool $responseAsString): self
    {
        $this->responseAsString = $responseAsString;
        return $this;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;
        return $this;
    }

    public function setEncoding(string $encoding): self
    {
        $this->encoding = $encoding;
        return $this;
    }

    public function setXmlStandalone(?bool $xmlStandalone): self
    {
        $this->xmlStandalone = $xmlStandalone;
        return $this;
    }

    public function setDocumentURI(?string $documentURI): self
    {
        $this->documentURI = $documentURI;
        return $this;
    }

    public function setFormatOutput(bool $formatOutput): self
    {
        $this->formatOutput = $formatOutput;
        return $this;
    }

    public function setPrefix(?string $prefix): self
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function setNamespaceURI(?string $namespaceURI): self
    {
        $this->namespaceURI = $namespaceURI;
        return $this;
    }

    public function setPreserveWhiteSpace(?bool $preserveWhiteSpace): self
    {
        $this->preserveWhiteSpace = $preserveWhiteSpace;
        return $this;
    }

    public function setValidateOnParse(?bool $validateOnParse): self
    {
        $this->validateOnParse = $validateOnParse;
        return $this;
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

    public function marshal(object $paxbObject): string|DOMDocument
    {
        $reflectionObject = new ReflectionObject($paxbObject);
        $attributesRoot   = $reflectionObject->getAttributes();
        $rootElement      = $this->retrieveRootElement($attributesRoot, $reflectionObject->getShortName());
        $rootNamespace    = $this->retrieveNamespaceRootElement($attributesRoot);
        $rootElementInfo  = $this->retrieveRootElementInfo($attributesRoot);

        $dom = $this->buildDom();
        
        // Create root element with namespace and prefix if provided
        $qualifiedName = $rootElement;
        if ($rootElementInfo && $rootElementInfo->namespacePrefix && $rootNamespace) {
            $qualifiedName = $rootElementInfo->namespacePrefix . ':' . $rootElement;
        }
        
        if ($rootNamespace !== null) {
            $root = $dom->createElementNS($rootNamespace, $qualifiedName);
        } else {
            $root = $dom->createElement($qualifiedName);
        }
        
        // Add namespace declarations
        if ($rootElementInfo && !empty($rootElementInfo->namespaceDeclarations)) {
            foreach ($rootElementInfo->namespaceDeclarations as $prefix => $uri) {
                $attrName = $prefix === '' ? 'xmlns' : 'xmlns:' . $prefix;
                $root->setAttributeNS('http://www.w3.org/2000/xmlns/', $attrName, $uri);
            }
        }
        
        // Add root attributes (like xsi:schemaLocation)
        if ($rootElementInfo && !empty($rootElementInfo->rootAttributes)) {
            foreach ($rootElementInfo->rootAttributes as $attrName => $attrValue) {
                if (str_contains($attrName, ':')) {
                    [$prefix, $localName] = explode(':', $attrName, 2);
                    // Find namespace URI for the prefix
                    $nsUri = $rootElementInfo->namespaceDeclarations[$prefix] ?? null;
                    if ($nsUri) {
                        $root->setAttributeNS($nsUri, $attrName, $attrValue);
                    } else {
                        $root->setAttribute($attrName, $attrValue);
                    }
                } else {
                    $root->setAttribute($attrName, $attrValue);
                }
            }
        }
        
        $root = $this->reflectionProperties($reflectionObject, $paxbObject, $dom, $root, $rootNamespace, $rootElementInfo);

        $dom->appendChild($root);

        return $this->responseAsString ? $dom->saveXML() : $dom;
    }

    private function marshallChild(ReflectionProperty $reflectionProperty, object $objectInstance, DOMDocument $dom, ?string $defaultNamespace = null, ?XmlRootElement $rootElementInfo = null): ?\DOMElement
    {
        $tagName = $this->getTagName($reflectionProperty);
        $elementInfo = $this->getElementInfo($reflectionProperty);
        
        // Use namespace from element or default namespace
        $namespace = $elementInfo?->namespace ?? $defaultNamespace;
        $prefix = $elementInfo?->namespacePrefix ?? $rootElementInfo?->namespacePrefix;
        
        $qualifiedName = $tagName;
        if ($prefix && $namespace) {
            $qualifiedName = $prefix . ':' . $tagName;
        }
        
        if ($namespace !== null) {
            $root = $dom->createElementNS($namespace, $qualifiedName);
        } else {
            $root = $dom->createElement($qualifiedName);
        }

        $paxbObject       = $this->getTagValue($reflectionProperty, $objectInstance);
        $reflectionObject = new ReflectionObject($paxbObject);

        return $this->reflectionProperties($reflectionObject, $paxbObject, $dom, $root, $namespace, $rootElementInfo);
    }

    private function reflectionProperties(ReflectionObject $reflectionObject, object $paxbObject, DOMDocument $dom, \DOMElement $root, ?string $defaultNamespace = null, ?XmlRootElement $rootElementInfo = null): \DOMElement
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

            if ($this->isXmlValue($reflectionProperty)) {
                $root->nodeValue = $this->getTagValue($reflectionProperty, $paxbObject);
                continue;
            }

            $isChild = $this->isChild($reflectionProperty, $paxbObject);
            if ($isChild) {
                $child = $this->marshallChild($reflectionProperty, $paxbObject, $dom, $defaultNamespace, $rootElementInfo);
                if ($child !== null)
                    $root->appendChild($child);

                continue;
            }

            $tagValue = $this->getTagValue($reflectionProperty, $paxbObject);
            if ($tagValue === null)
                continue;

            if ($this->isAttribute($reflectionProperty)) {
                $attributeXmlName = $this->getAttributeXmlName($reflectionProperty);
                if ($root instanceof \DOMElement) {
                    $root->setAttribute($attributeXmlName, (string)$tagValue);
                }
            } elseif (is_array($tagValue)) {
                $tagName = $this->getTagName($reflectionProperty);
                $elementInfo = $this->getElementInfo($reflectionProperty);
                $namespace = $elementInfo?->namespace ?? $defaultNamespace;
                $prefix = $elementInfo?->namespacePrefix ?? $rootElementInfo?->namespacePrefix;
                
                foreach ($tagValue as $value) {
                    $qualifiedName = $tagName;
                    if ($prefix && $namespace) {
                        $qualifiedName = $prefix . ':' . $tagName;
                    }
                    
                    if ($namespace !== null) {
                        $tagElement = $dom->createElementNS($namespace, $qualifiedName);
                    } else {
                        $tagElement = $dom->createElement($qualifiedName);
                    }
                    
                    if (is_object($value)) {
                        $reflectionObject = new ReflectionObject($value);
                        $childNode = $this->reflectionProperties($reflectionObject, $value, $dom, $tagElement, $namespace, $rootElementInfo);
                        if ($childNode === null)
                            continue;

                        $root->appendChild($childNode);
                    } else {
                        $tagName = $this->getTagName($reflectionProperty);
                        $qualifiedName = $tagName;
                        if ($prefix && $namespace) {
                            $qualifiedName = $prefix . ':' . $tagName;
                        }
                        
                        if ($namespace !== null) {
                            $child = $dom->createElementNS($namespace, $qualifiedName, (string)$tagValue);
                        } else {
                            $child = $dom->createElement($qualifiedName, (string)$tagValue);
                        }
                        $root->appendChild($child);
                    }
                }

                if (isset($tagElement))
                    $root->appendChild($tagElement);
            } else {
                $tagName = $this->getTagName($reflectionProperty);
                $elementInfo = $this->getElementInfo($reflectionProperty);
                $namespace = $elementInfo?->namespace ?? $defaultNamespace;
                $prefix = $elementInfo?->namespacePrefix ?? $rootElementInfo?->namespacePrefix;
                
                $qualifiedName = $tagName;
                if ($prefix && $namespace) {
                    $qualifiedName = $prefix . ':' . $tagName;
                }
                
                if ($namespace !== null) {
                    $child = $dom->createElementNS($namespace, $qualifiedName, (string)$tagValue);
                } else {
                    $child = $dom->createElement($qualifiedName, (string)$tagValue);
                }
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

    private function isXmlValue(ReflectionProperty $reflectionProperty): bool
    {
        $attributes = $reflectionProperty->getAttributes();
        if (count($attributes) <= 0)
            return false;

        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            if ($attributeInstance instanceof XmlValue)
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
     * @param array<ReflectionAttribute> $attributes
     */
    private function retrieveRootElementInfo(array $attributes): ?XmlRootElement
    {
        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            if ($attributeInstance instanceof XmlRootElement)
                return $attributeInstance;
        }

        return null;
    }

    private function getElementInfo(ReflectionProperty $reflectionProperty): ?\Jeidison\PAXB\Attributes\XmlElement
    {
        $attributes = $reflectionProperty->getAttributes();
        if (count($attributes) <= 0)
            return null;

        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            if ($attributeInstance instanceof \Jeidison\PAXB\Attributes\XmlElement)
                return $attributeInstance;
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
