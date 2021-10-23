<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlAttribute;
use Jeidison\PAXB\Attributes\XmlElement;
use Jeidison\PAXB\Attributes\XmlRootElement;

#[XmlRootElement('Diploma', 'http://portal.mec.gov.br/diplomadigital/arquivos-em-xsd')]
class Diploma
{
    #[XmlAttribute('xmlns:ns2')]
    private string $xmlnsNs2 = "http://www.w3.org/2000/09/xmldsig#";

    #[XmlElement('infDiploma')]
    private InfDiploma $infDiploma;

    private $signature = null;

    public function getXmlnsNs2(): string
    {
        return $this->xmlnsNs2;
    }

    public function setXmlnsNs2(string $xmlnsNs2): void
    {
        $this->xmlnsNs2 = $xmlnsNs2;
    }

    public function getInfDiploma(): InfDiploma
    {
        return $this->infDiploma;
    }

    public function setInfDiploma(InfDiploma $infDiploma): void
    {
        $this->infDiploma = $infDiploma;
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public function setSignature($signature): void
    {
        $this->signature = $signature;
    }
}

