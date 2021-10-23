<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use Jeidison\PAXB\Attributes\XmlElement;

class TituloConferido
{
    #[XmlElement('Titulo')]
    private ?string $titulo = null;

    #[XmlElement('OutroTitulo')]
    private ?string $outroTitulo = null;

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
        return $this;
    }

    public function getOutroTitulo()
    {
        return $this->outroTitulo;
    }

    public function setOutroTitulo($outroTitulo)
    {
        $this->outroTitulo = $outroTitulo;
        return $this;
    }
}

