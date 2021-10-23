<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlElement;

class Naturalidade
{
    #[XmlElement('CodigoMunicipio')]
    private ?string $codigoMunicipio = null;

    #[XmlElement('NomeMunicipio')]
    private ?string $nomeMunicipio = null;

    #[XmlElement('UF')]
    private ?string $uf = null;

    #[XmlElement('NomeMunicipioEstrangeiro')]
    private ?string $nomeMunicipioEstrangeiro = null;

    public function getCodigoMunicipio(): ?string
    {
        return $this->codigoMunicipio;
    }

    public function setCodigoMunicipio(?string $codigoMunicipio): void
    {
        $this->codigoMunicipio = $codigoMunicipio;
    }

    public function getNomeMunicipio(): ?string
    {
        return $this->nomeMunicipio;
    }

    public function setNomeMunicipio(?string $nomeMunicipio): void
    {
        $this->nomeMunicipio = $nomeMunicipio;
    }

    public function getUf(): ?string
    {
        return $this->uf;
    }

    public function setUf(?string $uf): void
    {
        $this->uf = $uf;
    }

    public function getNomeMunicipioEstrangeiro(): ?string
    {
        return $this->nomeMunicipioEstrangeiro;
    }

    public function setNomeMunicipioEstrangeiro(?string $nomeMunicipioEstrangeiro): void
    {
        $this->nomeMunicipioEstrangeiro = $nomeMunicipioEstrangeiro;
    }

}

