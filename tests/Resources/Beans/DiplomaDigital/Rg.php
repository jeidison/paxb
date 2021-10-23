<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlElement;

class Rg
{
    #[XmlElement('Numero')]
    private ?string $numero = null;

    #[XmlElement('OrgaoExpedidor')]
    private ?string $orgaoExpedidor = null;

    #[XmlElement('UF')]
    private ?string $uf = null;

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): void
    {
        $this->numero = $numero;
    }

    public function getOrgaoExpedidor(): ?string
    {
        return $this->orgaoExpedidor;
    }

    public function setOrgaoExpedidor(?string $orgaoExpedidor): void
    {
        $this->orgaoExpedidor = $orgaoExpedidor;
    }

    public function getUf(): ?string
    {
        return $this->uf;
    }

    public function setUf(?string $uf): void
    {
        $this->uf = $uf;
    }

}

