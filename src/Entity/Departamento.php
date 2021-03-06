<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartamentoRepository")
 */
class Departamento
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $provincia;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Localidad", mappedBy="departamento", orphanRemoval=true)
     */
    private $localidades;

    public function __construct()
    {
        $this->localidades = new ArrayCollection();
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

    /**
     * @return Collection|Localidad[]
     */
    public function getLocalidades(): Collection
    {
        return $this->localidades;
    }

    public function addLocalidade(Localidad $localidade): self
    {
        if (!$this->localidades->contains($localidade)) {
            $this->localidades[] = $localidade;
            $localidade->setDepartamento($this);
        }

        return $this;
    }

    public function removeLocalidade(Localidad $localidade): self
    {
        if ($this->localidades->contains($localidade)) {
            $this->localidades->removeElement($localidade);
            // set the owning side to null (unless already changed)
            if ($localidade->getDepartamento() === $this) {
                $localidade->setDepartamento(null);
            }
        }

        return $this;
    }

    public function getProvincia(): ?int
    {
        return $this->provincia;
    }

    public function setProvincia(?int $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }
}
