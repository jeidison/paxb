<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlElement;

class DadosCursoNSF
{
    #[XmlElement('NomeCurso')]
    private ?string $nomeCurso = null;

    #[XmlElement('CodigoCursoEMEC')]
    private ?int $codigoCursoEMEC = null;

    #[XmlElement('SemCodigoCursoEMEC')]
    private ?InformacoesTramitacaoEMEC $semCodigoCursoEMEC = null;

    #[XmlElement('NomeHabilitacao')]
    private ?string $nomeHabilitacao = null;

    #[XmlElement('Modalidade')]
    private ?string $modalidade = null;

    #[XmlElement('TituloConferido')]
    private ?TituloConferido $tituloConferido = null;

    #[XmlElement('GrauConferido')]
    private ?string $grauConferido = null;

    #[XmlElement('EnderecoCurso')]
    private ?Endereco $enderecoCurso = null;

    #[XmlElement('Polo')]
    private ?Polo $polo = null;

    #[XmlElement('Autorizacao')]
    private ?AtoRegulatorioComOuSemEMEC $autorizacao = null;

    #[XmlElement('Reconhecimento')]
    private ?AtoRegulatorioComOuSemEMEC $reconhecimento = null;

    #[XmlElement('RenovacaoReconhecimento')]
    private ?AtoRegulatorioComOuSemEMEC $renovacaoReconhecimento = null;

    public function getNomeCurso(): ?string
    {
        return $this->nomeCurso;
    }

    public function setNomeCurso(?string $nomeCurso): void
    {
        $this->nomeCurso = $nomeCurso;
    }

    public function getCodigoCursoEMEC(): ?int
    {
        return $this->codigoCursoEMEC;
    }

    public function setCodigoCursoEMEC(?int $codigoCursoEMEC): void
    {
        $this->codigoCursoEMEC = $codigoCursoEMEC;
    }

    public function getSemCodigoCursoEMEC(): ?InformacoesTramitacaoEMEC
    {
        return $this->semCodigoCursoEMEC;
    }

    public function setSemCodigoCursoEMEC(?InformacoesTramitacaoEMEC $semCodigoCursoEMEC): void
    {
        $this->semCodigoCursoEMEC = $semCodigoCursoEMEC;
    }

    public function getNomeHabilitacao(): ?string
    {
        return $this->nomeHabilitacao;
    }

    public function setNomeHabilitacao(?string $nomeHabilitacao): void
    {
        $this->nomeHabilitacao = $nomeHabilitacao;
    }

    public function getModalidade(): ?string
    {
        return $this->modalidade;
    }

    public function setModalidade(?string $modalidade): void
    {
        $this->modalidade = $modalidade;
    }

    public function getTituloConferido(): ?TituloConferido
    {
        return $this->tituloConferido;
    }

    public function setTituloConferido(?TituloConferido $tituloConferido): void
    {
        $this->tituloConferido = $tituloConferido;
    }

    public function getGrauConferido(): ?string
    {
        return $this->grauConferido;
    }

    public function setGrauConferido(?string $grauConferido): void
    {
        $this->grauConferido = $grauConferido;
    }

    public function getEnderecoCurso(): ?Endereco
    {
        return $this->enderecoCurso;
    }

    public function setEnderecoCurso(?Endereco $enderecoCurso): void
    {
        $this->enderecoCurso = $enderecoCurso;
    }

    public function getPolo(): ?Polo
    {
        return $this->polo;
    }

    public function setPolo(?Polo $polo): void
    {
        $this->polo = $polo;
    }

    public function getAutorizacao(): ?AtoRegulatorioComOuSemEMEC
    {
        return $this->autorizacao;
    }

    public function setAutorizacao(?AtoRegulatorioComOuSemEMEC $autorizacao): void
    {
        $this->autorizacao = $autorizacao;
    }

    public function getReconhecimento(): ?AtoRegulatorioComOuSemEMEC
    {
        return $this->reconhecimento;
    }

    public function setReconhecimento(?AtoRegulatorioComOuSemEMEC $reconhecimento): void
    {
        $this->reconhecimento = $reconhecimento;
    }

    public function getRenovacaoReconhecimento(): ?AtoRegulatorioComOuSemEMEC
    {
        return $this->renovacaoReconhecimento;
    }

    public function setRenovacaoReconhecimento(?AtoRegulatorioComOuSemEMEC $renovacaoReconhecimento): void
    {
        $this->renovacaoReconhecimento = $renovacaoReconhecimento;
    }

}

