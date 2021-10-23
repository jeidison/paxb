<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlElement;

class Mantenedora
{
    #[XmlElement('RazaoSocial')]
    private ?string $razaoSocial = null;

    #[XmlElement('CNPJ')]
    private ?string $cnpj = null;

    #[XmlElement('Endereco')]
    private ?Endereco $endereco = null;

    public function getRazaoSocial(): ?string
    {
        return $this->razaoSocial;
    }

    public function setRazaoSocial(?string $razaoSocial): void
    {
        $this->razaoSocial = $razaoSocial;
    }

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function setCnpj(?string $cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    public function getEndereco(): ?Endereco
    {
        return $this->endereco;
    }

    public function setEndereco(?Endereco $endereco): void
    {
        $this->endereco = $endereco;
    }

}

