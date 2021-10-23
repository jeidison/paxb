<?php

namespace Jeidison\PAXB\Xsd\ClassBuilder;

use DateTime;
use Jeidison\PAXB\Xsd\PrimitiveTypes;
use SimpleXMLElement;
use stdClass;

class ExtractorType
{
    private array $simpleTypeData = [];
    private array $complexTypeData = [];

    public static function instance(): self
    {
        return new self();
    }

    public function complexTypeData(SimpleXMLElement $xsd, array &$simpleTypeData): array
    {
        if ($xsd->complexType->count() <= 0)
            return [];

        foreach ($xsd->complexType as $node) {
            $complexType             = new stdClass();
            $complexType->type       = (string)$node['name'];
            $complexType->annotation = (string)$node?->annotation?->documentation ?? '';

            $nodeSequence = $node->sequence;
            if (isset($nodeSequence->choice)) {
                $strSequence  = str_replace('<choice>', '', $nodeSequence->asXML());
                $strSequence  = str_replace('</choice>', '', $strSequence);
                $nodeSequence = new SimpleXMLElement($strSequence);
            }

            if ($nodeSequence->element == null)
                continue;

            foreach ($node->attribute as $nodeAttributes) {
                $xmlAttribute = $this->extractAttribute($nodeAttributes, $simpleTypeData);
                $complexType->xmlAttributes[] = $xmlAttribute;
            }

            foreach ($nodeSequence->element as $xmlElement) {
                if (str_contains($xmlElement->asXML(), ' ref="'))
                    continue;

                if ($element = $this->extractElement($xmlElement, $simpleTypeData))
                    $complexType->xmlElements[] = $element;
            }

            $this->complexTypeData[$complexType->type] = $complexType;
        }

        return $this->complexTypeData;
    }

    protected function getType(object $xmlAttributes, SimpleXMLElement $xmlElement, array &$simpleTypeData): string
    {
        $type = $xmlAttributes->type ?? "";
        if (isset($simpleTypeData[$type]))
            return $simpleTypeData[$type]->restriction ?? "";

        if ($xmlElement->simpleType->count() <= 0)
            return $type;

        $simpleType = (string)$xmlElement->simpleType->restriction['base'];
        if (in_array($simpleType, PrimitiveTypes::COMPLETE_LIST))
            return $simpleType;

        if (isset($simpleTypeData[$simpleType]))
            return $simpleTypeData[$simpleType]->restriction ?? "";

        $restriction = $xmlElement->simpleType?->restriction;

        $simpleTypeNew             = new stdClass();
        $simpleTypeNew->type       = $simpleType;
        $simpleTypeNew->annotation = (string)$xmlElement->simpleType?->annotation?->documentation ?? '';

        $simpleTypeNew->restriction = in_array($simpleType, ['ID']) ? 'string' : '';
        $simpleTypeNew->minLength   = (string)$restriction?->minLength['value'] ?? '';
        $simpleTypeNew->maxLength   = (string)$restriction?->maxLength['value'] ?? '';
        $simpleTypeNew->pattern     = (string)$restriction?->pattern['value']   ?? '';

        $simpleTypeData[$simpleTypeNew->type] = $simpleTypeNew;

        return $simpleTypeNew->restriction ?? "";
    }

    private function extractElement(SimpleXMLElement $xmlElement, array &$simpleTypeData): ?stdClass
    {
        $xmlAttributes = $this->getAttributesAsObject($xmlElement);

        if (isset($xmlElement->complexType)) {
            $complexType             = new stdClass();
            $complexType->type       = (string)$xmlElement['name'];
            $complexType->annotation = (string)$xmlElement?->annotation?->documentation ?? '';

            $nodeSequence = $xmlElement->complexType->sequence;
            if (isset($nodeSequence->choice)) {
                $strSequence  = str_replace('<choice>', '', $nodeSequence->asXML());
                $strSequence  = str_replace('</choice>', '', $strSequence);
                $nodeSequence = new SimpleXMLElement($strSequence);
            }

            if ($nodeSequence->element == null)
                return $complexType;

            foreach ($nodeSequence->element as $element) {
                $xmlAttributes->type    = $complexType->type;
                $nodeSequenceAttributes = $this->getAttributesAsObject($nodeSequence);
                if ($nodeSequenceAttributes != null) {
                    $xmlAttributes->minOccurs = $nodeSequenceAttributes?->minOccurs ?? '';
                    $xmlAttributes->maxOccurs = $nodeSequenceAttributes?->maxOccurs ?? '';
                }

                if ($elementExtracted = $this->extractElement($element, $simpleTypeData))
                    $complexType->xmlElements[] = $elementExtracted;
            }

            $this->complexTypeData[$complexType->type] = $complexType;
        }

        $element             = new stdClass();
        $element->minOccurs  = $xmlAttributes->minOccurs ?? '';
        $element->maxOccurs  = $xmlAttributes->maxOccurs ?? '';
        $element->name       = $xmlAttributes->name ?? '';
        $element->annotation = (string)$xmlElement?->annotation?->documentation ?? '';
        $element->type       = $this->getType($xmlAttributes, $xmlElement, $simpleTypeData);

        return $element;
    }

    protected function extractAttribute(SimpleXMLElement $elementXmlAttribute, array &$simpleTypeData): stdClass
    {
        $xmlAttributes = $this->getAttributesAsObject($elementXmlAttribute);
        if (isset($xmlAttributes->type))
            $xmlAttributes->type = $xmlAttributes->type;
        else
            $xmlAttributes->type = (string)$elementXmlAttribute?->simpleType?->restriction['base'];

        $attributeXml              = new stdClass();
        $attributeXml->name        = (string)$xmlAttributes->name;
        $attributeXml->required    = (string)$xmlAttributes->{'use'} == 'required';
        $attributeXml->annotation  = (string)$elementXmlAttribute?->annotation?->documentation ?? '';
        $simpleType                = $elementXmlAttribute?->simpleType;
        $attributeXml->type        = $this->getType($xmlAttributes, $elementXmlAttribute, $simpleTypeData);
        $attributeXml->minLength   = (string)$simpleType?->restriction?->minLength['value'] ?? '';
        $attributeXml->maxLength   = (string)$simpleType?->restriction?->maxLength['value'] ?? '';
        $attributeXml->pattern     = (string)$simpleType?->restriction?->pattern['value']   ?? '';

        return $attributeXml;
    }

    public function simpleTypeData(SimpleXMLElement $xsd): array
    {
        foreach ($xsd->simpleType as $node) {
            $simpleType             = new stdClass();
            $simpleType->type       = (string)$node['name'];
            $simpleType->annotation = (string)$node?->annotation?->documentation ?? '';

            $simpleType->restriction = (string)$node->restriction['base'];
            $simpleType->minLength   = (string)$node?->restriction?->minLength['value'] ?? '';
            $simpleType->maxLength   = (string)$node?->restriction?->maxLength['value'] ?? '';
            $simpleType->pattern     = (string)$node?->restriction?->pattern['value']   ?? '';

            $this->simpleTypeData[$simpleType->type] = $simpleType;
        }

        foreach ($this->simpleTypeData as &$simpleType) {
            if (in_array($simpleType->restriction, PrimitiveTypes::COMPLETE_LIST))
                continue;

            $fromTo = ['unsignedInt' => 'int', 'decimal' => 'float', 'date' => DateTime::class];
            if (array_key_exists($simpleType->restriction, $fromTo)) {
                $simpleType->restrictionOri = $simpleType->restriction;
                $simpleType->restriction    = $fromTo[$simpleType->restriction];
                continue;
            }

            if (!array_key_exists($simpleType->restriction, $this->simpleTypeData))
                continue;

            $type = $this->simpleTypeData[$simpleType->restriction]->restriction;
            if (array_key_exists($type, $fromTo))
                $type = $fromTo[$type];

            $simpleType->restrictionOri = $simpleType->restriction;
            $simpleType->restriction    = $type;
        }

        return $this->simpleTypeData;
    }

    private function getAttributesAsObject(SimpleXMLElement $element): ?object
    {
        $attributes = json_decode(json_encode($element->attributes()));
        return $attributes?->{"@attributes"} ?? null;
    }

}