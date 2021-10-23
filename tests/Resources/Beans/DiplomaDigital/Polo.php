<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlElement;

class Polo
{
    #[XmlElement('Nome')]
    private ?string $nome = null;

    #[XmlElement('Endereco')]
    private ?Endereco $endereco = null;

    #[XmlElement('CodigoEMEC')]
    private ?int $codigoEMEC = null;

    #[XmlElement('SemCodigoEMEC')]
    private ?InformacoesTramitacaoEMEC $semCodigoEMEC = null;

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function setEndereco(Endereco $endereco)
    {
        $this->endereco = $endereco;
        return $this;
    }

    public function getCodigoEMEC()
    {
        return $this->codigoEMEC;
    }

    public function setCodigoEMEC($codigoEMEC)
    {
        $this->codigoEMEC = $codigoEMEC;
        return $this;
    }

    public function getSemCodigoEMEC()
    {
        return $this->semCodigoEMEC;
    }

    public function setSemCodigoEMEC(InformacoesTramitacaoEMEC $semCodigoEMEC)
    {
        $this->semCodigoEMEC = $semCodigoEMEC;
        return $this;
    }
}

