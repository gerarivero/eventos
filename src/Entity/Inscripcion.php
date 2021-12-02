<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InscripcionRepository")
 * @UniqueEntity(
 *     fields={"curso", "alumno"},
 *     message="Ya se encuentra inscripto en este curso",
 *     ignoreNull=false
 * )
 * @ORM\Table(uniqueConstraints={
 *  @UniqueConstraint(name="searchalumno_idx", columns={"alumno_id", "curso_id"})
 * })
 */
class Inscripcion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Curso", inversedBy="inscripciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $curso;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Alumno", inversedBy="inscripciones")
     * @ORM\JoinColumn()
     */
    private $alumno;

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
    private $codigo;

    public function __construct()
    {
        $this->notificacions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->curso."-".$this->alumno."-".$this->estado;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurso(): ?Curso
    {
        return $this->curso;
    }

    public function setCurso(?Curso $curso): self
    {
        $this->curso = $curso;

        return $this;
    }

    public function getAlumno(): ?Alumno
    {
        return $this->alumno;
    }

    public function setAlumno(?Alumno $alumno): self
    {
        $this->alumno = $alumno;

        return $this;
    }

    public function getAlumnoNombre(): ?string
    {
        //dump($this->alumno->getPersona()->getNombre());die();
        return $this->alumno->getPersona()->getNombre();
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

    public function getUltimaNotificacion(){        
        if($this->notificacions->last()){
            return $this->notificacions->last()->getFecha()->format('d/m/Y H:i:s')."|".$this->notificacions->last()->getEstado();
        }        
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

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }    
}
