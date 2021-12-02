<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Persona.
 *
 * @ORM\Table(name="persona")
 * @ORM\Entity(repositoryClass="App\Repository\PersonaRepository")
 * @ORM\Table(uniqueConstraints={
 *  @UniqueConstraint(name="dni_idx", columns={"dni"})
 * })
 */
class Persona
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
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=255)    
     */
    protected $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)     
     */
    protected $nombre;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean", options={"default" : 0}, nullable=true)
     */
    protected $activo;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email()     
     */
    protected $email;

    /**
     * @var array
     *
     * @ORM\Column(name="telefono", type="string", nullable=true)
     */
    protected $telefono;

    /**
     * @var array
     *
     * @ORM\Column(name="telefono_fijo", type="string", nullable=true)
     */
    protected $telefonoFijo;

    /**
     * @var array
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    protected $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="dni", type="string", length=255)     
     */
    protected $dni;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaNacimiento;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Localidad", inversedBy="personas")
     * @ORM\JoinColumn()
     */
    private $localidad;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Alumno", mappedBy="persona", cascade={"persist", "remove"})
     */
    private $alumno;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InscripcionCongreso", mappedBy="persona", orphanRemoval=true)
     */
    private $inscripcionesCongreso;

    public function __toString()
    {
        return $this->nombre."|".$this->apellido."|".$this->dni;
    }

    public function __construct()
    {
        $this->inscripciones = new ArrayCollection();
        $this->activo = true;
        $this->inscripcionesCongreso = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(?\DateTimeInterface $fechaNacimiento): self
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
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

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(?bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getLocalidad(): ?Localidad
    {
        return $this->localidad;
    }

    public function setLocalidad(?Localidad $localidad): self
    {
        $this->localidad = $localidad;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getTelefonoFijo(): ?string
    {
        return $this->telefonoFijo;
    }

    public function setTelefonoFijo(?string $telefonoFijo): self
    {
        $this->telefonoFijo = $telefonoFijo;

        return $this;
    }

    public function getAlumno(): ?Alumno
    {
        return $this->alumno;
    }

    public function setAlumno(?Alumno $alumno): self
    {
        $this->alumno = $alumno;

        // set (or unset) the owning side of the relation if necessary
        $newPersona = $alumno === null ? null : $this;
        if ($newPersona !== $alumno->getPersona()) {
            $alumno->setPersona($newPersona);
        }

        return $this;
    }

    /**
     * @return Collection|InscripcionCongreso[]
     */
    public function getInscripcionesCongreso(): Collection
    {
        return $this->inscripcionesCongreso;
    }

    public function addInscripcionesCongreso(InscripcionCongreso $inscripcionesCongreso): self
    {
        if (!$this->inscripcionesCongreso->contains($inscripcionesCongreso)) {
            $this->inscripcionesCongreso[] = $inscripcionesCongreso;
            $inscripcionesCongreso->setPersona($this);
        }

        return $this;
    }

    public function removeInscripcionesCongreso(InscripcionCongreso $inscripcionesCongreso): self
    {
        if ($this->inscripcionesCongreso->contains($inscripcionesCongreso)) {
            $this->inscripcionesCongreso->removeElement($inscripcionesCongreso);
            // set the owning side to null (unless already changed)
            if ($inscripcionesCongreso->getPersona() === $this) {
                $inscripcionesCongreso->setPersona(null);
            }
        }

        return $this;
    }
}
