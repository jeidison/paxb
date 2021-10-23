<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use DateTime;
use Jeidison\PAXB\Attributes\Adapters\DateAmericanAdapter;
use Jeidison\PAXB\Attributes\Adapters\XmlPhpTypeAdapter;
use Jeidison\PAXB\Attributes\XmlAttribute;
use Jeidison\PAXB\Attributes\XmlElement;

class DadosDiplomaNSF
{
    #[XmlAttribute]
    private ?string $id = null;

    #[XmlElement('Diplomado')]
    private ?Diplomado $diplomado = null;

    #[XmlElement('DataConclusao')]
    #[XmlPhpTypeAdapter(DateAmericanAdapter::class)]
    private ?DateTime $dataConclusao = null;

    #[XmlElement('DadosCurso')]
    private ?DadosCursoNSF $dadosCurso = null;

    #[XmlElement('IesEmissora')]
    private ?IesEmissora $iesEmissora = null;

    #[XmlElement('Signature')]
    private $signature = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getDiplomado(): ?Diplomado
    {
        return $this->diplomado;
    }

    public function setDiplomado(?Diplomado $diplomado): void
    {
        $this->diplomado = $diplomado;
    }

    public function getDataConclusao(): ?DateTime
    {
        return $this->dataConclusao;
    }

    public function setDataConclusao(?DateTime $dataConclusao): void
    {
        $this->dataConclusao = $dataConclusao;
    }

    public function getDadosCurso(): ?DadosCursoNSF
    {
        return $this->dadosCurso;
    }

    public function setDadosCurso(?DadosCursoNSF $dadosCurso): void
    {
        $this->dadosCurso = $dadosCurso;
    }

    public function getIesEmissora(): ?IesEmissora
    {
        return $this->iesEmissora;
    }

    public function setIesEmissora(?IesEmissora $iesEmissora): void
    {
        $this->iesEmissora = $iesEmissora;
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

