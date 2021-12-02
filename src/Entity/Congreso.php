<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CongresoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Congreso
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clave;

    /**
     * @ORM\Column(type="array", nullable=false)
     */
    private $fechas = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InscripcionCongreso", mappedBy="congreso")
     */
    private $inscripciones;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plantilla", inversedBy="congresos")
     */
    private $plantilla;

    public function __construct()
    {        
        $this->inscripciones = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getFechas(): ?array
    {
        return $this->fechas;
    }

    public function setFechas(array $fechas): self
    {
        $this->fechas = $fechas;

        return $this;
    }

    /**
     * @return Collection|InscripcionCongreso[]
     */
    public function getInscripciones(): Collection
    {
        return $this->inscripciones;
    }

    public function addInscripcione(InscripcionCongreso $inscripcione): self
    {
        if (!$this->inscripciones->contains($inscripcione)) {
            $this->inscripciones[] = $inscripcione;
            $inscripcione->setCongreso($this);
        }

        return $this;
    }

    public function removeInscripcione(InscripcionCongreso $inscripcione): self
    {
        if ($this->inscripciones->contains($inscripcione)) {
            $this->inscripciones->removeElement($inscripcione);
            // set the owning side to null (unless already changed)
            if ($inscripcione->getCongreso() === $this) {
                $inscripcione->setCongreso(null);
            }
        }

        return $this;
    }

    public function getPlantilla(): ?Plantilla
    {
        return $this->plantilla;
    }

    public function setPlantilla(?Plantilla $plantilla): self
    {
        $this->plantilla = $plantilla;

        return $this;
    }

    public function getClave(): ?string
    {
        return $this->clave;
    }

    public function setClave(?string $clave): self
    {
        $this->clave = $clave;

        return $this;
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove() {
        if ($this->inscripciones->count()>0) {            
            throw new Exception('No puede eliminar un congreso con inscriptos.');
        }
    }
}
