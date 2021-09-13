<?php

namespace Jeidison\PAXB\Commons;

use Jeidison\PAXB\Attributes\Element;
use Jeidison\PAXB\Attributes\XmlAttribute;
use Jeidison\PAXB\Attributes\XmlRootElement;
use ReflectionAttribute;
use ReflectionObject;
use ReflectionProperty;

trait MarshallerCommons
{
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

    private function normalizeTagName(string $tagName): string
    {
        return lcfirst(ucwords($tagName));
    }
}