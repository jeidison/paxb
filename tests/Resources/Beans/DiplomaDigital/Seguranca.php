<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlElement;

class Seguranca
{
    #[XmlElement('CodigoValidacao')]
    private ?string $codigoValidacao = null;

    public function getCodigoValidacao()
    {
        return $this->codigoValidacao;
    }

    public function setCodigoValidacao($codigoValidacao)
    {
        $this->codigoValidacao = $codigoValidacao;
        return $this;
    }
}

