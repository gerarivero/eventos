<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificacionRepository")
 */
class Notificacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @ORM\ManyToOne(targetEntity="App\Entity\Inscripcion", inversedBy="notificacions")
     */
    private $inscripcion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

    public function __toString()
    {
        return $this->fecha->format("d/m/Y")."|".$this->estado;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInscripcion(): ?Inscripcion
    {
        return $this->inscripcion;
    }

    public function setInscripcion(?Inscripcion $inscripcion): self
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

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }
}
