<?php

namespace Jeidison\PAXB\Xsd;

use Jeidison\PAXB\Exception\Xsd2PhpException;
use Jeidison\PAXB\Xsd\ClassBuilder\ExtractorType;
use Jeidison\PAXB\Xsd\Converter\ComplexTypeClassConverter;
use Jeidison\PAXB\Xsd\Converter\ConverterParameter;
use SimpleXMLElement;
use stdClass;

class Xsd2Php
{
    private array $simpleTypeData = [];
    private array $complexTypeData = [];
    private array $xsds;

    public static function instance(): self
    {
        if (!class_exists(\PhpParser\BuilderFactory::class))
            throw new Xsd2PhpException("Para gerar as classes através do XSD por favor instale a pacote 'nikic/php-parser:^4.* -dev'");

        return new self();
    }

    public function convert(Xsd2PhpParameter $xsd2PhpParameter)
    {
        if (!is_dir($xsd2PhpParameter->pathStoreClasses))
            throw new Xsd2PhpException("Path para salvar as classes inválido.");

        $xsdAsString  = str_replace('xs:', '', file_get_contents($xsd2PhpParameter->pathRootXsd));
        $xsdAsString = str_replace('ds:', '', $xsdAsString);
        $xsd          = new SimpleXMLElement($xsdAsString);
        $this->xsds[] = $xsd;
        $this->loadIncludes($xsd, $xsd2PhpParameter);
        $this->loadImports($xsd2PhpParameter);

        $this->extractSimpleTypeData();
        $this->extractComplexTypeData();
        $this->processRootElement();

        $parameter = new ConverterParameter;
        $parameter->complexTypes = $this->complexTypeData;
        $parameter->simpleTypes  = $this->simpleTypeData;
        $parameter->withSetters  = $xsd2PhpParameter->withSetters;
        $parameter->withGetters  = $xsd2PhpParameter->withGetters;
        $parameter->namespace    = $xsd2PhpParameter->namespace;

        $converter = new ComplexTypeClassConverter();
        $generator = $converter->convert($parameter);
        foreach ($generator as $generatorStrClass) {
            list($className, $strClass) = $generatorStrClass;
            file_put_contents($xsd2PhpParameter->pathStoreClasses."/$className.php", $strClass);
        }
    }

    protected function loadImports(Xsd2PhpParameter $xsd2PhpParameter): ?SimpleXMLElement
    {
        foreach ($this->xsds as $xsd) {
            $xsdAsString = $this->loadImport($xsd, $xsd2PhpParameter);
            if ($xsdAsString == null)
                continue;

            $xsdImported  = new SimpleXMLElement($xsdAsString);
            $this->xsds[] = $xsdImported;
        }

        return $xsdImported ?? null;
    }

    protected function loadIncludes(SimpleXMLElement $xsd, Xsd2PhpParameter $xsd2PhpParameter): SimpleXMLElement
    {
        $xsdAsString  = $this->loadInclude($xsd, $xsd2PhpParameter);
        $xsdImported  = new SimpleXMLElement($xsdAsString);
        $this->xsds[] = $xsdImported;
        if (str_contains($xsdAsString, 'schemaLocation="'))
            return $this->loadIncludes($xsdImported, $xsd2PhpParameter);

        return $xsdImported;
    }

    protected function loadImport(SimpleXMLElement $xsd, Xsd2PhpParameter $xsd2PhpParameter): ?string
    {
        if (!property_exists($xsd, 'import'))
            return null;

        $attributes = $this->getAttributesAsObject($xsd->import);
        if ($attributes == null)
            return null;

        if (!property_exists($attributes, 'schemaLocation'))
            return null;

        $xsdAsString = file_get_contents(dirname($xsd2PhpParameter->pathRootXsd) . DIRECTORY_SEPARATOR . $attributes->schemaLocation);
        $xsdAsString = str_replace('ds:', '', $xsdAsString);
        return str_replace('xs:', '', $xsdAsString);
    }

    protected function loadInclude(SimpleXMLElement $xsd, Xsd2PhpParameter $xsd2PhpParameter): ?string
    {
        if (!property_exists($xsd, 'include'))
            return null;

        $attributes = $this->getAttributesAsObject($xsd->include);
        if ($attributes == null)
            return null;

        if (!property_exists($attributes, 'schemaLocation'))
            return null;

        $xsdAsString = file_get_contents(dirname($xsd2PhpParameter->pathRootXsd) . DIRECTORY_SEPARATOR . $attributes->schemaLocation);
        $xsdAsString = str_replace('ds:', '', $xsdAsString);
        return str_replace('xs:', '', $xsdAsString);
    }

    protected function extractSimpleTypeData()
    {
       foreach ($this->xsds as $xsd) {
           $simpleTypes = ExtractorType::instance()->simpleTypeData($xsd);
           $this->simpleTypeData = array_merge($this->simpleTypeData, $simpleTypes);
       }
    }

    protected function extractComplexTypeData()
    {
        foreach ($this->xsds as $xsd) {
            $complexTypes          = ExtractorType::instance()->groupData($xsd, $this->simpleTypeData);
            $this->complexTypeData = array_merge($this->complexTypeData, $complexTypes);
        }

        foreach ($this->xsds as $xsd) {
            $complexTypes          = ExtractorType::instance()->complexTypeData($xsd, $this->simpleTypeData, $this->complexTypeData);
            $this->complexTypeData = array_merge($this->complexTypeData, $complexTypes);
        }


    }

    protected function processRootElement()
    {
        $xsd        = $this->xsds[0];
        $attributes = $this->getAttributesAsObject($xsd->element);

        $complexType             = new stdClass();
        $complexType->type       = (string)$attributes->type;
        $complexType->rootName   = (string)$attributes->name;
        $complexType->annotation = (string)$xsd->element?->annotation?->documentation ?? '';

        $this->complexTypeData = array_merge($this->complexTypeData, [$complexType->rootName => $complexType]);
    }

    private function getAttributesAsObject(SimpleXMLElement $element): ?object
    {
        $attributes = json_decode(json_encode($element->attributes()));
        return $attributes?->{"@attributes"};
    }

}