<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlumnoRepository")
 */
class Alumno
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ocupacion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nivelEducativo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $discapacidad;

    /**
     * @ORM\Column(type="array")
     */
    private $tipoDiscapacidad;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cud;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $vencCud;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $apoyosTecnicos;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $medioInfo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $temasInteres;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inscripcion", mappedBy="alumno", orphanRemoval=true)
     */
    private $inscripciones;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Persona", inversedBy="alumno", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $persona;

    public function __toString()
    {
        return $this->persona->__toString();
    }

    public function __construct()
    {
        $this->inscripciones = new ArrayCollection();
        $this->activo = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOcupacion(): ?string
    {
        return $this->ocupacion;
    }

    public function setOcupacion(?string $ocupacion): self
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    public function getNivelEducativo(): ?string
    {
        return $this->nivelEducativo;
    }

    public function setNivelEducativo(string $nivelEducativo): self
    {
        $this->nivelEducativo = $nivelEducativo;

        return $this;
    }

    public function getDiscapacidad(): ?bool
    {
        return $this->discapacidad;
    }

    public function setDiscapacidad(?bool $discapacidad): self
    {
        $this->discapacidad = $discapacidad;

        return $this;
    }

    public function getTipoDiscapacidad(): ?array
    {
        return $this->tipoDiscapacidad;
    }

    public function setTipoDiscapacidad(array $tipoDiscapacidad): self
    {
        $this->tipoDiscapacidad = $tipoDiscapacidad;

        return $this;
    }

    public function getCud(): ?bool
    {
        return $this->cud;
    }

    public function setCud(?bool $cud): self
    {
        $this->cud = $cud;

        return $this;
    }

    public function getVencCud(): ?\DateTimeInterface
    {
        return $this->vencCud;
    }

    public function setVencCud(?\DateTimeInterface $vencCud): self
    {
        $this->vencCud = $vencCud;

        return $this;
    }

    public function getApoyosTecnicos(): ?string
    {
        return $this->apoyosTecnicos;
    }

    public function setApoyosTecnicos(?string $apoyosTecnicos): self
    {
        $this->apoyosTecnicos = $apoyosTecnicos;

        return $this;
    }

    public function getMedioInfo(): ?string
    {
        return $this->medioInfo;
    }

    public function setMedioInfo(?string $medioInfo): self
    {
        $this->medioInfo = $medioInfo;

        return $this;
    }

    public function getTemasInteres(): ?string
    {
        return $this->temasInteres;
    }

    public function setTemasInteres(?string $temasInteres): self
    {
        $this->temasInteres = $temasInteres;

        return $this;
    }

    /**
     * @return Collection|Inscripcion[]
     */
    public function getInscripciones(): Collection
    {
        return $this->inscripciones;
    }

    public function addInscripcione(Inscripcion $inscripcione): self
    {
        if (!$this->inscripciones->contains($inscripcione)) {
            $this->inscripciones[] = $inscripcione;
            $inscripcione->setAlumno($this);
        }

        return $this;
    }

    public function removeInscripcione(Inscripcion $inscripcione): self
    {
        if ($this->inscripciones->contains($inscripcione)) {
            $this->inscripciones->removeElement($inscripcione);
            // set the owning side to null (unless already changed)
            if ($inscripcione->getAlumno() === $this) {
                $inscripcione->setAlumno(null);
            }
        }

        return $this;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(Persona $persona): self
    {
        $this->persona = $persona;

        return $this;
    }

    public function getListTipoDiscapacidad()
    {        
        $string = "";
        foreach ($this->tipoDiscapacidad as $key => $discapacidad){
            dump($discapacidad);
            $string.= $discapacidad . "|";
        }        
        return $string;
    }
}
