<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlElement;

class Endereco
{
    #[XmlElement('Logradouro')]
    private ?string $logradouro = null;

    #[XmlElement('Numero')]
    private ?string $numero = null;

    #[XmlElement('Complemento')]
    private ?string $complemento = null;

    #[XmlElement('Bairro')]
    private ?string $bairro = null;

    #[XmlElement('CodigoMunicipio')]
    private ?string $codigoMunicipio = null;

    #[XmlElement('NomeMunicipio')]
    private ?string $nomeMunicipio = null;

    #[XmlElement('UF')]
    private ?string $uf = null;

    #[XmlElement('NomeMunicipioEstrangeiro')]
    private ?string $nomeMunicipioEstrangeiro = null;

    #[XmlElement('CEP')]
    private ?string $cep = null;

    public function getLogradouro(): ?string
    {
        return $this->logradouro;
    }

    public function setLogradouro(?string $logradouro): void
    {
        $this->logradouro = $logradouro;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): void
    {
        $this->numero = $numero;
    }

    public function getComplemento(): ?string
    {
        return $this->complemento;
    }

    public function setComplemento(?string $complemento): void
    {
        $this->complemento = $complemento;
    }

    public function getBairro(): ?string
    {
        return $this->bairro;
    }

    public function setBairro(?string $bairro): void
    {
        $this->bairro = $bairro;
    }

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

    public function getCep(): ?string
    {
        return $this->cep;
    }

    public function setCep(?string $cep): void
    {
        $this->cep = $cep;
    }
}

