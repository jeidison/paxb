<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use DateTime;
use Jeidison\PAXB\Attributes\Adapters\DateAmericanAdapter;
use Jeidison\PAXB\Attributes\Adapters\XmlPhpTypeAdapter;
use Jeidison\PAXB\Attributes\XmlAttribute;
use Jeidison\PAXB\Attributes\XmlElement;

class DadosDiploma
{
    #[XmlAttribute('id')]
    private string $id;

    #[XmlElement('Diplomado')]
    private Diplomado $diplomado;

    #[XmlElement('DataConclusao')]
    #[XmlPhpTypeAdapter(DateAmericanAdapter::class)]
    private ?DateTime $dataConclusao = null;

    #[XmlElement('DadosCurso')]
    private DadosCurso $dadosCurso;

    #[XmlElement('IesEmissora')]
    private IesEmissora $iesEmissora;

    private $signature = [];

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getDiplomado(): Diplomado
    {
        return $this->diplomado;
    }

    public function setDiplomado(Diplomado $diplomado): void
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

    public function getDadosCurso(): DadosCurso
    {
        return $this->dadosCurso;
    }

    public function setDadosCurso(DadosCurso $dadosCurso): void
    {
        $this->dadosCurso = $dadosCurso;
    }

    public function getIesEmissora(): IesEmissora
    {
        return $this->iesEmissora;
    }

    public function setIesEmissora(IesEmissora $iesEmissora): void
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

