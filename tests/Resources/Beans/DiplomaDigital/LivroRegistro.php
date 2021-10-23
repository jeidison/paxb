<?php

namespace Jeidison\PAXB\Tests\Resources\Beans\DiplomaDigital;

use DateTime;
use Jeidison\PAXB\Attributes\Adapters\DateAmericanAdapter;
use Jeidison\PAXB\Attributes\Adapters\XmlPhpTypeAdapter;
use Jeidison\PAXB\Attributes\XmlElement;

class LivroRegistro
{
    #[XmlElement('LivroRegistro')]
    private ?string $livroRegistro = null;

    #[XmlElement('NumeroRegistro')]
    private ?string $numeroRegistro = null;

    #[XmlElement('NumeroFolhaDoDiploma')]
    private ?string $numeroFolhaDoDiploma = null;

    #[XmlElement('NumeroSequenciaDoDiploma')]
    private ?string $numeroSequenciaDoDiploma = null;

    #[XmlElement('ProcessoDoDiploma')]
    private ?string $processoDoDiploma = null;

    #[XmlElement('DataColacaoGrau')]
    #[XmlPhpTypeAdapter(DateAmericanAdapter::class)]
    private ?DateTime $dataColacaoGrau = null;

    #[XmlElement('DataExpedicaoDiploma')]
    #[XmlPhpTypeAdapter(DateAmericanAdapter::class)]
    private ?DateTime $dataExpedicaoDiploma = null;

    #[XmlElement('DataRegistroDiploma')]
    #[XmlPhpTypeAdapter(DateAmericanAdapter::class)]
    private ?DateTime $dataRegistroDiploma = null;

    #[XmlElement('ResponsavelRegistro')]
    private ?ResponsavelRegistro $responsavelRegistro = null;

    public function getLivroRegistro(): ?string
    {
        return $this->livroRegistro;
    }

    public function setLivroRegistro(?string $livroRegistro): void
    {
        $this->livroRegistro = $livroRegistro;
    }

    public function getNumeroRegistro(): ?string
    {
        return $this->numeroRegistro;
    }

    public function setNumeroRegistro(?string $numeroRegistro): void
    {
        $this->numeroRegistro = $numeroRegistro;
    }

    public function getNumeroFolhaDoDiploma(): ?string
    {
        return $this->numeroFolhaDoDiploma;
    }

    public function setNumeroFolhaDoDiploma(?string $numeroFolhaDoDiploma): void
    {
        $this->numeroFolhaDoDiploma = $numeroFolhaDoDiploma;
    }

    public function getNumeroSequenciaDoDiploma(): ?string
    {
        return $this->numeroSequenciaDoDiploma;
    }

    public function setNumeroSequenciaDoDiploma(?string $numeroSequenciaDoDiploma): void
    {
        $this->numeroSequenciaDoDiploma = $numeroSequenciaDoDiploma;
    }

    public function getProcessoDoDiploma(): ?string
    {
        return $this->processoDoDiploma;
    }

    public function setProcessoDoDiploma(?string $processoDoDiploma): void
    {
        $this->processoDoDiploma = $processoDoDiploma;
    }

    public function getDataColacaoGrau(): ?DateTime
    {
        return $this->dataColacaoGrau;
    }

    public function setDataColacaoGrau(?DateTime $dataColacaoGrau): void
    {
        $this->dataColacaoGrau = $dataColacaoGrau;
    }

    public function getDataExpedicaoDiploma(): ?DateTime
    {
        return $this->dataExpedicaoDiploma;
    }

    public function setDataExpedicaoDiploma(?DateTime $dataExpedicaoDiploma): void
    {
        $this->dataExpedicaoDiploma = $dataExpedicaoDiploma;
    }

    public function getDataRegistroDiploma(): ?DateTime
    {
        return $this->dataRegistroDiploma;
    }

    public function setDataRegistroDiploma(?DateTime $dataRegistroDiploma): void
    {
        $this->dataRegistroDiploma = $dataRegistroDiploma;
    }

    public function getResponsavelRegistro(): ?ResponsavelRegistro
    {
        return $this->responsavelRegistro;
    }

    public function setResponsavelRegistro(?ResponsavelRegistro $responsavelRegistro): void
    {
        $this->responsavelRegistro = $responsavelRegistro;
    }

}

