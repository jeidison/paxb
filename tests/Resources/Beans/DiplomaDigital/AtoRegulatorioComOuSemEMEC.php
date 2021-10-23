<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use DateTime;
use Jeidison\PAXB\Attributes\XmlElement;
use Jeidison\PAXB\Attributes\Adapters\DateAmericanAdapter;
use Jeidison\PAXB\Attributes\Adapters\XmlPhpTypeAdapter;

class AtoRegulatorioComOuSemEMEC
{
    #[XmlElement('Tipo')]
    private ?string $tipo = null;

    #[XmlElement('Numero')]
    private ?string $numero = null;

    #[XmlElement('Data')]
    #[XmlPhpTypeAdapter(DateAmericanAdapter::class)]
    private ?DateTime $data = null;

    #[XmlElement('VeiculoPublicacao')]
    private ?string $veiculoPublicacao = null;

    #[XmlElement('DataPublicacao')]
    #[XmlPhpTypeAdapter(DateAmericanAdapter::class)]
    private ?DateTime $dataPublicacao = null;

    #[XmlElement('SecaoPublicacao')]
    private ?int $secaoPublicacao = null;

    #[XmlElement('PaginaPublicacao')]
    private ?int $paginaPublicacao = null;

    #[XmlElement('NumeroDOU')]
    private ?int $numeroDOU = null;

    #[XmlElement('InformacoesTramitacaoEMEC')]
    private ?InformacoesTramitacaoEMEC $informacoesTramitacaoEMEC = null;

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData(DateTime $data)
    {
        $this->data = $data;
        return $this;
    }

    public function getVeiculoPublicacao()
    {
        return $this->veiculoPublicacao;
    }

    public function setVeiculoPublicacao($veiculoPublicacao)
    {
        $this->veiculoPublicacao = $veiculoPublicacao;
        return $this;
    }

    public function getDataPublicacao()
    {
        return $this->dataPublicacao;
    }

    public function setDataPublicacao(DateTime $dataPublicacao)
    {
        $this->dataPublicacao = $dataPublicacao;
        return $this;
    }

    public function getSecaoPublicacao()
    {
        return $this->secaoPublicacao;
    }

    public function setSecaoPublicacao($secaoPublicacao)
    {
        $this->secaoPublicacao = $secaoPublicacao;
        return $this;
    }

    public function getPaginaPublicacao()
    {
        return $this->paginaPublicacao;
    }

    public function setPaginaPublicacao($paginaPublicacao)
    {
        $this->paginaPublicacao = $paginaPublicacao;
        return $this;
    }

    public function getNumeroDOU()
    {
        return $this->numeroDOU;
    }

    public function setNumeroDOU($numeroDOU)
    {
        $this->numeroDOU = $numeroDOU;
        return $this;
    }

    public function getInformacoesTramitacaoEMEC()
    {
        return $this->informacoesTramitacaoEMEC;
    }

    public function setInformacoesTramitacaoEMEC(InformacoesTramitacaoEMEC $informacoesTramitacaoEMEC)
    {
        $this->informacoesTramitacaoEMEC = $informacoesTramitacaoEMEC;
        return $this;
    }

}

