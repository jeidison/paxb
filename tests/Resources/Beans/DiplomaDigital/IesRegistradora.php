<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlElement;

class IesRegistradora
{
    #[XmlElement('Nome')]
    private ?string $nome = null;

    #[XmlElement('CodigoMEC')]
    private ?int $codigoMEC = null;

    #[XmlElement('CNPJ')]
    private ?string $cnpj = null;

    #[XmlElement('Endereco')]
    private ?Endereco $endereco = null;

    #[XmlElement('Credenciamento')]
    private ?AtoRegulatorioComOuSemEMEC $credenciamento = null;

    #[XmlElement('Recredenciamento')]
    private ?AtoRegulatorioComOuSemEMEC $recredenciamento = null;

    #[XmlElement('RenovacaoDeRecredenciamento')]
    private ?AtoRegulatorioComOuSemEMEC $renovacaoDeRecredenciamento = null;

    #[XmlElement('Mantenedora')]
    private ?Mantenedora $mantenedora = null;

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    public function getCodigoMEC(): ?int
    {
        return $this->codigoMEC;
    }

    public function setCodigoMEC(?int $codigoMEC): void
    {
        $this->codigoMEC = $codigoMEC;
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

    public function getCredenciamento(): ?AtoRegulatorioComOuSemEMEC
    {
        return $this->credenciamento;
    }

    public function setCredenciamento(?AtoRegulatorioComOuSemEMEC $credenciamento): void
    {
        $this->credenciamento = $credenciamento;
    }

    public function getRecredenciamento(): ?AtoRegulatorioComOuSemEMEC
    {
        return $this->recredenciamento;
    }

    public function setRecredenciamento(?AtoRegulatorioComOuSemEMEC $recredenciamento): void
    {
        $this->recredenciamento = $recredenciamento;
    }

    public function getRenovacaoDeRecredenciamento(): ?AtoRegulatorioComOuSemEMEC
    {
        return $this->renovacaoDeRecredenciamento;
    }

    public function setRenovacaoDeRecredenciamento(?AtoRegulatorioComOuSemEMEC $renovacaoDeRecredenciamento): void
    {
        $this->renovacaoDeRecredenciamento = $renovacaoDeRecredenciamento;
    }

    public function getMantenedora(): ?Mantenedora
    {
        return $this->mantenedora;
    }

    public function setMantenedora(?Mantenedora $mantenedora): void
    {
        $this->mantenedora = $mantenedora;
    }

}

