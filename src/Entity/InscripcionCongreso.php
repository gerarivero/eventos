<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InscripcionCongresoRepository")
 * @UniqueEntity(
 *     fields={"congreso", "persona", "condicion"},
 *     errorPath="condicion",
 *     message="la persona no puede repetir la inscripcion al congreso con la misma condición",
 *     ignoreNull=false
 * )
 * @ORM\Table(uniqueConstraints={
 *  @UniqueConstraint(name="search_idx", columns={"congreso_id", "persona_id", "condicion"}),
 *  @UniqueConstraint(name="searchnull_idx", columns={"congreso_id", "persona_id"})
 * })
 */
class InscripcionCongreso
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Congreso", inversedBy="inscripciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $congreso;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Persona", inversedBy="inscripcionesCongreso")
     * @ORM\JoinColumn()
     */
    private $persona;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $estado;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $porcAsistencia;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notificacion", mappedBy="inscripcion", cascade={"persist"})
     */
    private $notificacions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $condicion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codigo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Acreditacion", mappedBy="inscripcion", orphanRemoval=true)
     */
    private $acreditacions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codigoCupon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lugarAdquisicion;

    public function __construct()
    {
        $this->notificacions = new ArrayCollection();
        $this->acreditacions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->congreso."-".$this->persona;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getPorcAsistencia(): ?int
    {
        return $this->porcAsistencia;
    }

    public function setPorcAsistencia(?int $porcAsistencia): self
    {
        $this->porcAsistencia = $porcAsistencia;

        return $this;
    }

    public function getCondicion(): ?string
    {
        return $this->condicion;
    }

    public function setCondicion(?string $condicion): self
    {
        $this->condicion = $condicion;

        return $this;
    }

    public function getCongreso(): ?Congreso
    {
        return $this->congreso;
    }

    public function setCongreso(?Congreso $congreso): self
    {
        $this->congreso = $congreso;

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * @return Collection|Acreditacion[]
     */
    public function getAcreditacions(): Collection
    {
        return $this->acreditacions;
    }

    public function addAcreditacion(Acreditacion $acreditacion): self
    {
        if (!$this->acreditacions->contains($acreditacion)) {
            $this->acreditacions[] = $acreditacion;
            $acreditacion->setInscripcion($this);
        }

        return $this;
    }

    public function removeAcreditacion(Acreditacion $acreditacion): self
    {
        if ($this->acreditacions->contains($acreditacion)) {
            $this->acreditacions->removeElement($acreditacion);
            // set the owning side to null (unless already changed)
            if ($acreditacion->getInscripcion() === $this) {
                $acreditacion->setInscripcion(null);
            }
        }

        return $this;
    }

    public function isAcreditado($fecha){
        $acreditaciones = $this->getAcreditacions();
        $array = [];
        foreach($acreditaciones as $acreditacion){
            $array[] = $acreditacion->getFecha();
        }
        
        return in_array($fecha, $array);
    }

    public function getCodigoCupon(): ?string
    {
        return $this->codigoCupon;
    }

    public function setCodigoCupon(?string $codigoCupon): self
    {
        $this->codigoCupon = $codigoCupon;

        return $this;
    }

    public function getLugarAdquisicion(): ?string
    {
        return $this->lugarAdquisicion;
    }

    public function setLugarAdquisicion(?string $lugarAdquisicion): self
    {
        $this->lugarAdquisicion = $lugarAdquisicion;

        return $this;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(?Persona $persona): self
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * @return Collection|Notificacion[]
     */
    public function getNotificacions(): Collection
    {
        return $this->notificacions;
    }

    public function addNotificacion(Notificacion $notificacion): self
    {
        if (!$this->notificacions->contains($notificacion)) {
            $this->notificacions[] = $notificacion;
            $notificacion->setInscripcion($this);
        }

        return $this;
    }

    public function removeNotificacion(Notificacion $notificacion): self
    {
        if ($this->notificacions->contains($notificacion)) {
            $this->notificacions->removeElement($notificacion);
            // set the owning side to null (unless already changed)
            if ($notificacion->getInscripcion() === $this) {
                $notificacion->setInscripcion(null);
            }
        }

        return $this;
    }
}
