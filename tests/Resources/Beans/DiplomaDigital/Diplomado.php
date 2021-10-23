<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use DateTime;
use Jeidison\PAXB\Attributes\Adapters\DateAmericanAdapter;
use Jeidison\PAXB\Attributes\Adapters\XmlPhpTypeAdapter;
use Jeidison\PAXB\Attributes\XmlElement;

class Diplomado
{
    #[XmlElement('ID')]
    private string $id;

    #[XmlElement('Nome')]
    private string $nome;

    #[XmlElement('NomeSocial')]
    private string $nomeSocial;

    #[XmlElement('Sexo')]
    private string $sexo;

    #[XmlElement('Nacionalidade')]
    private string $nacionalidade;

    #[XmlElement('Naturalidade')]
    private Naturalidade $naturalidade;

    #[XmlElement('CPF')]
    private string $cpf;

    #[XmlElement('RG')]
    private Rg $rg;

    #[XmlElement('OutroDocumentoIdentificacao')]
    private ?OutroDocumentoIdentificacao $outroDocumentoIdentificacao = null;

    #[XmlPhpTypeAdapter(DateAmericanAdapter::class)]
    #[XmlElement('DataNascimento')]
    private DateTime $dataNascimento;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getNomeSocial(): string
    {
        return $this->nomeSocial;
    }

    public function setNomeSocial(string $nomeSocial): void
    {
        $this->nomeSocial = $nomeSocial;
    }

    public function getSexo(): string
    {
        return $this->sexo;
    }

    public function setSexo(string $sexo): void
    {
        $this->sexo = $sexo;
    }

    public function getNacionalidade(): string
    {
        return $this->nacionalidade;
    }

    public function setNacionalidade(string $nacionalidade): void
    {
        $this->nacionalidade = $nacionalidade;
    }

    public function getNaturalidade(): Naturalidade
    {
        return $this->naturalidade;
    }

    public function setNaturalidade(Naturalidade $naturalidade): void
    {
        $this->naturalidade = $naturalidade;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function getRg(): Rg
    {
        return $this->rg;
    }

    public function setRg(Rg $rg): void
    {
        $this->rg = $rg;
    }

    public function getOutroDocumentoIdentificacao(): ?OutroDocumentoIdentificacao
    {
        return $this->outroDocumentoIdentificacao;
    }

    public function setOutroDocumentoIdentificacao(?OutroDocumentoIdentificacao $outroDocumentoIdentificacao): void
    {
        $this->outroDocumentoIdentificacao = $outroDocumentoIdentificacao;
    }

    public function getDataNascimento(): DateTime
    {
        return $this->dataNascimento;
    }

    public function setDataNascimento(DateTime $dataNascimento): void
    {
        $this->dataNascimento = $dataNascimento;
    }

}

