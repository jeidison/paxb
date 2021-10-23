<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlElement;

class OutroDocumentoIdentificacao
{
    #[XmlElement('TipoDocumento')]
    private ?string $tipoDocumento = null;

    #[XmlElement('Identificador')]
    private ?string $identificador = null;

    public function getTipoDocumento(): ?string
    {
        return $this->tipoDocumento;
    }

    public function setTipoDocumento(?string $tipoDocumento): void
    {
        $this->tipoDocumento = $tipoDocumento;
    }

    public function getIdentificador(): ?string
    {
        return $this->identificador;
    }

    public function setIdentificador(?string $identificador): void
    {
        $this->identificador = $identificador;
    }

}

