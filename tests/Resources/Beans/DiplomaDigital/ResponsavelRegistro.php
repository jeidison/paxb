<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlElement;

class ResponsavelRegistro
{
    #[XmlElement('Nome')]
    private ?string $nome = null;

    #[XmlElement('CPF')]
    private ?string $cpf = null;

    #[XmlElement('IDouNumeroMatricula')]
    private ?string $iDouNumeroMatricula = null;

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(?string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function getIDouNumeroMatricula(): ?string
    {
        return $this->iDouNumeroMatricula;
    }

    public function setIDouNumeroMatricula(?string $iDouNumeroMatricula): void
    {
        $this->iDouNumeroMatricula = $iDouNumeroMatricula;
    }

}

