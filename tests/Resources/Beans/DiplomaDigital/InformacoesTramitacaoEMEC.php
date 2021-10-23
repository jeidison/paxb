<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use DateTime;
use Jeidison\PAXB\Attributes\Adapters\DateAmericanAdapter;
use Jeidison\PAXB\Attributes\Adapters\XmlPhpTypeAdapter;
use Jeidison\PAXB\Attributes\XmlElement;

class InformacoesTramitacaoEMEC
{
    #[XmlElement('NumeroProcesso')]
    private ?int $numeroProcesso = null;

    #[XmlElement('TipoProcesso')]
    private ?string $tipoProcesso = null;

    #[XmlElement('DataCadastro')]
    #[XmlPhpTypeAdapter(DateAmericanAdapter::class)]
    private ?DateTime $dataCadastro = null;

    #[XmlElement('DataProtocolo')]
    #[XmlPhpTypeAdapter(DateAmericanAdapter::class)]
    private ?DateTime $dataProtocolo = null;

    public function getNumeroProcesso()
    {
        return $this->numeroProcesso;
    }

    public function setNumeroProcesso(?int $numeroProcesso)
    {
        $this->numeroProcesso = $numeroProcesso;
        return $this;
    }

    public function getTipoProcesso()
    {
        return $this->tipoProcesso;
    }

    public function setTipoProcesso(string $tipoProcesso)
    {
        $this->tipoProcesso = $tipoProcesso;
        return $this;
    }

    public function getDataCadastro()
    {
        return $this->dataCadastro;
    }

    public function setDataCadastro(DateTime $dataCadastro)
    {
        $this->dataCadastro = $dataCadastro;
        return $this;
    }

    public function getDataProtocolo()
    {
        return $this->dataProtocolo;
    }

    public function setDataProtocolo(DateTime $dataProtocolo)
    {
        $this->dataProtocolo = $dataProtocolo;
        return $this;
    }
}

