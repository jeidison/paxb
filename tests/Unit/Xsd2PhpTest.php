<?php

namespace Jeidison\PAXB\Tests\Unit;

use Jeidison\PAXB\Tests\TestCase;
use Jeidison\PAXB\Xsd\Xsd2Php;
use Jeidison\PAXB\Xsd\Xsd2PhpParameter;

class Xsd2PhpTest extends TestCase
{
    public function testConvertXsdToClassPhp()
    {
        $parameter = new Xsd2PhpParameter;
        $parameter->pathRootXsd = __DIR__.'/../Resources/Xsd/Diploma/DiplomaDigital_v1.02.xsd';
        $parameter->withSetters = true;
        $parameter->withGetters = true;
        $parameter->namespace = __NAMESPACE__;
        $parameter->pathStoreClasses = __DIR__.'/A';

        Xsd2Php::instance()->convert($parameter);

    }
}