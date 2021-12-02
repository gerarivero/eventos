<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QrRepository")
 */
class Qr
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $tituloQr;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $nombreEvento;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $apellido;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $dni;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cargaHoraria;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $asistencia;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $fechas;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $textoAdicional;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Plantilla", mappedBy="qr")
     */
    private $plantillas;

    public function __construct()
    {
        $this->plantillas = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->tituloQr;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?bool
    {
        return $this->nombre;
    }

    public function setNombre(?bool $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellido(): ?bool
    {
        return $this->apellido;
    }

    public function setApellido(?bool $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getDni(): ?bool
    {
        return $this->dni;
    }

    public function setDni(?bool $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * @return Collection|Plantilla[]
     */
    public function getPlantillas(): Collection
    {
        return $this->plantillas;
    }

    public function addPlantilla(Plantilla $plantilla): self
    {
        if (!$this->plantillas->contains($plantilla)) {
            $this->plantillas[] = $plantilla;
            $plantilla->setQr($this);
        }

        return $this;
    }

    public function removePlantilla(Plantilla $plantilla): self
    {
        if ($this->plantillas->contains($plantilla)) {
            $this->plantillas->removeElement($plantilla);
            // set the owning side to null (unless already changed)
            if ($plantilla->getQr() === $this) {
                $plantilla->setQr(null);
            }
        }

        return $this;
    }

    public function getTituloQr(): ?string
    {
        return $this->tituloQr;
    }

    public function setTituloQr(string $tituloQr): self
    {
        $this->tituloQr = $tituloQr;

        return $this;
    }

    public function getNombreEvento(): ?bool
    {
        return $this->nombreEvento;
    }

    public function setNombreEvento(?bool $nombreEvento): self
    {
        $this->nombreEvento = $nombreEvento;

        return $this;
    }

    public function getTextoAdicional(): ?string
    {
        return $this->textoAdicional;
    }

    public function setTextoAdicional(?string $textoAdicional): self
    {
        $this->textoAdicional = $textoAdicional;

        return $this;
    }

    public function getCargaHoraria(): ?bool
    {
        return $this->cargaHoraria;
    }

    public function setCargaHoraria(?bool $cargaHoraria): self
    {
        $this->cargaHoraria = $cargaHoraria;

        return $this;
    }

    public function getAsistencia(): ?bool
    {
        return $this->asistencia;
    }

    public function setAsistencia(?bool $asistencia): self
    {
        $this->asistencia = $asistencia;

        return $this;
    }

    public function getFechas(): ?bool
    {
        return $this->fechas;
    }

    public function setFechas(?bool $fechas): self
    {
        $this->fechas = $fechas;

        return $this;
    }
}
