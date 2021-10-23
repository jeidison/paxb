<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlAttribute;
use Jeidison\PAXB\Attributes\XmlElement;

class DadosRegistroNSF
{
    #[XmlAttribute('id')]
    private string $id;

    #[XmlElement('IesRegistradora')]
    private ?IesRegistradora $iesRegistradora = null;

    #[XmlElement('LivroRegistro')]
    private ?LivroRegistro $livroRegistro = null;

    #[XmlElement('IdDocumentacaoAcademica')]
    private ?string $idDocumentacaoAcademica = null;

    #[XmlElement('Seguranca')]
    private ?Seguranca $seguranca = null;

    #[XmlElement('Signature')]
    private $signature = [];

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getIesRegistradora(): ?IesRegistradora
    {
        return $this->iesRegistradora;
    }

    public function setIesRegistradora(?IesRegistradora $iesRegistradora): void
    {
        $this->iesRegistradora = $iesRegistradora;
    }

    public function getLivroRegistro(): ?LivroRegistro
    {
        return $this->livroRegistro;
    }

    public function setLivroRegistro(?LivroRegistro $livroRegistro): void
    {
        $this->livroRegistro = $livroRegistro;
    }

    public function getIdDocumentacaoAcademica(): ?string
    {
        return $this->idDocumentacaoAcademica;
    }

    public function setIdDocumentacaoAcademica(?string $idDocumentacaoAcademica): void
    {
        $this->idDocumentacaoAcademica = $idDocumentacaoAcademica;
    }

    public function getSeguranca(): ?Seguranca
    {
        return $this->seguranca;
    }

    public function setSeguranca(?Seguranca $seguranca): void
    {
        $this->seguranca = $seguranca;
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

