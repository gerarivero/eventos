<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AcreditacionRepository")
 */
class Acreditacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InscripcionCongreso", inversedBy="acreditacions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $inscripcion;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInscripcion(): ?InscripcionCongreso
    {
        return $this->inscripcion;
    }

    public function setInscripcion(?InscripcionCongreso $inscripcion): self
    {
        $this->inscripcion = $inscripcion;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }
}
