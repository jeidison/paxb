<?php

namespace Jeidison\PAXB\Tests\Unit;

use DateTime;
use Jeidison\PAXB\Attributes\XmlAttribute;
use Jeidison\PAXB\Tests\TestCase;
use Jeidison\PAXB\Xsd\ClassBuilder\AttributeTemplate;
use Jeidison\PAXB\Xsd\ClassBuilder\ClassBuilder;
use Jeidison\PAXB\Xsd\ClassBuilder\PropertyTemplate;

class ClassBuilderTest extends TestCase
{

    public function testClassBuilderBase()
    {
        $classBuilder = new ClassBuilder();
        $class = $classBuilder->withClassName("Pessoa")
                              ->withNamespace('Jeidison\PAXB\Tests\Unit')
                              ->build();

        $this->assertNotNull($class);
    }

    public function testClassBuilderWithProperties()
    {
        $classBuilder = new ClassBuilder();
        $class = $classBuilder->withClassName("Pessoa")
            ->withNamespace('Jeidison\PAXB\Tests\Unit')
            ->addProperty((new PropertyTemplate())->setType('string')->setName('primeiroNome'))
            ->addProperty((new PropertyTemplate())->setType('string')->setName('segundoNome'))
            ->build();

        $this->assertNotNull($class);
    }

    public function testClassBuilderWithPropertiesAndGetters()
    {
        $classBuilder = new ClassBuilder();
        $class = $classBuilder->withClassName("Pessoa")
            ->withNamespace('Jeidison\PAXB\Tests\Unit')
            ->withGetters(true)
            ->withSetters(false)
            ->addProperty((new PropertyTemplate())->setType('string')->setName('primeiroNome'))
            ->addProperty((new PropertyTemplate())->setType('string')->setName('segundoNome'))
            ->build();

        $this->assertNotNull($class);
        echo $class;
    }

    public function testClassBuilderWithPropertiesAndSetters()
    {
        $classBuilder = new ClassBuilder();
        $class = $classBuilder->withClassName("Pessoa")
            ->withNamespace('Jeidison\PAXB\Tests\Unit')
            ->withSetters(true)
            ->withGetters(false)
            ->addProperty((new PropertyTemplate())->setType('string')->setName('primeiroNome'))
            ->addProperty((new PropertyTemplate())->setType('string')->setName('segundoNome'))
            ->build();

        $this->assertNotNull($class);
    }

    public function testClassBuilderWithPropertiesAndGettersAndSetters()
    {
        $classBuilder = new ClassBuilder();
        $class = $classBuilder->withClassName("Pessoa")
            ->withNamespace('Jeidison\PAXB\Tests\Unit')
            ->withSetters(true)
            ->withGetters(true)
            ->addProperty((new PropertyTemplate())->setType('string')->setName('primeiroNome'))
            ->addProperty((new PropertyTemplate())->setType('string')->setName('segundoNome'))
            ->build();

        $this->assertNotNull($class);
    }

    public function testClassBuilderWithPropertiesAndGettersAndSettersAndAttributes()
    {
        $propertyTemplate = (new PropertyTemplate())
            ->setType('string')
            ->setName('primeiroNome')
            ->addAttribute(
                (new AttributeTemplate)->setType(XmlAttribute::class)
            );

        $classBuilder = new ClassBuilder();
        $class = $classBuilder->withClassName("Pessoa")
            ->withNamespace('Jeidison\PAXB\Tests\Unit')
            ->withSetters(true)
            ->withGetters(true)
            ->addProperty((new PropertyTemplate())->setType(DateTime::class)->setName('dataNascimento'))
            ->addProperty($propertyTemplate)
            ->addProperty((new PropertyTemplate())->setType('string')->setName('segundoNome'))
            ->build();

        $this->assertNotNull($class);
        echo $class;
    }


}