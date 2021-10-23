<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlAttribute;
use Jeidison\PAXB\Attributes\XmlElement;

class InfDiploma
{
    #[XmlAttribute]
    private string $id;

    #[XmlAttribute]
    private string $versao;

    #[XmlElement('DadosDiploma')]
    private DadosDiploma $dadosDiploma;

    #[XmlElement('DadosDiplomaNSF')]
    private DadosDiplomaNSF $dadosDiplomaNSF;

    #[XmlElement('DadosRegistro')]
    private DadosRegistro $dadosRegistro;

    #[XmlElement('DadosRegistroNSF')]
    private DadosRegistroNSF $dadosRegistroNSF;

    #[XmlElement('Signature')]
    private $signature = [];

    public function getVersao(): string
    {
        return $this->versao;
    }

    public function setVersao(string $versao): void
    {
        $this->versao = $versao;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getDadosDiploma(): DadosDiploma
    {
        return $this->dadosDiploma;
    }

    public function setDadosDiploma(DadosDiploma $dadosDiploma): void
    {
        $this->dadosDiploma = $dadosDiploma;
    }

    public function getDadosDiplomaNSF(): DadosDiplomaNSF
    {
        return $this->dadosDiplomaNSF;
    }

    public function setDadosDiplomaNSF(DadosDiplomaNSF $dadosDiplomaNSF): void
    {
        $this->dadosDiplomaNSF = $dadosDiplomaNSF;
    }

    public function getDadosRegistro(): DadosRegistro
    {
        return $this->dadosRegistro;
    }

    public function setDadosRegistro(DadosRegistro $dadosRegistro): void
    {
        $this->dadosRegistro = $dadosRegistro;
    }

    public function getDadosRegistroNSF(): DadosRegistroNSF
    {
        return $this->dadosRegistroNSF;
    }

    public function setDadosRegistroNSF(DadosRegistroNSF $dadosRegistroNSF): void
    {
        $this->dadosRegistroNSF = $dadosRegistroNSF;
    }

    public function getSignature(): array
    {
        return $this->signature;
    }

    public function setSignature(array $signature): void
    {
        $this->signature = $signature;
    }

}

