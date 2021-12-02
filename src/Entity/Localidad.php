<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocalidadRepository")
 */
class Localidad
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
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $codigoPostal;

     /**
     * @ORM\Column(type="integer", length=30, nullable=true)
     */
    private $microregionId;

     /**
     * @ORM\Column(type="integer", length=30, nullable=true)
     */
    private $municipioId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idrpcd;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Persona", mappedBy="localidad", orphanRemoval=true)
     */
    private $personas;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departamento", inversedBy="localidades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departamento;

    public function __construct()
    {
        $this->alumnos = new ArrayCollection();
        $this->personas = new ArrayCollection();
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

    public function getIdrpcd(): ?int
    {
        return $this->idrpcd;
    }

    public function setIdrpcd(?int $idrpcd): self
    {
        $this->idrpcd = $idrpcd;

        return $this;
    }

    public function getDepartamento(): ?Departamento
    {
        return $this->departamento;
    }

    public function setDepartamento(?Departamento $departamento): self
    {
        $this->departamento = $departamento;

        return $this;
    }

    public function getCodigoPostal(): ?string
    {
        return $this->codigoPostal;
    }

    public function setCodigoPostal(?string $codigoPostal): self
    {
        $this->codigoPostal = $codigoPostal;

        return $this;
    }

    public function getMicroregionId(): ?int
    {
        return $this->microregionId;
    }

    public function setMicroregionId(?int $microregionId): self
    {
        $this->microregionId = $microregionId;

        return $this;
    }

    public function getMunicipioId(): ?int
    {
        return $this->municipioId;
    }

    public function setMunicipioId(?int $municipioId): self
    {
        $this->municipioId = $municipioId;

        return $this;
    }

    /**
     * @return Collection|Persona[]
     */
    public function getPersonas(): Collection
    {
        return $this->personas;
    }

    public function addPersona(Persona $persona): self
    {
        if (!$this->personas->contains($persona)) {
            $this->personas[] = $persona;
            $persona->setLocalidad($this);
        }

        return $this;
    }

    public function removePersona(Persona $persona): self
    {
        if ($this->personas->contains($persona)) {
            $this->personas->removeElement($persona);
            // set the owning side to null (unless already changed)
            if ($persona->getLocalidad() === $this) {
                $persona->setLocalidad(null);
            }
        }

        return $this;
    }
}
