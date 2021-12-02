<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Docente
 * @ORM\Entity()
 * @ORM\Table(name="docente")
 */
class Docente
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
     * @ORM\Column(name="telefonos", type="array")
     */
    protected $telefonos;

    /**
     * @var array
     *
     * @ORM\Column(name="direcciones", type="array")
     */
    protected $direcciones;

    /**
     * @var string
     *
     * @ORM\Column(name="dni", type="string", length=255)     
     */
    protected $dni;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Curso", inversedBy="docentes")
     */
    private $cursos;

    public function __construct()
    {
        $this->cursos = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTelefonos(): ?array
    {
        return $this->telefonos;
    }

    public function setTelefonos(array $telefonos): self
    {
        $this->telefonos = $telefonos;

        return $this;
    }

    public function getDirecciones(): ?array
    {
        return $this->direcciones;
    }

    public function setDirecciones(array $direcciones): self
    {
        $this->direcciones = $direcciones;

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

    /**
     * @return Collection|Curso[]
     */
    public function getCursos(): Collection
    {
        return $this->cursos;
    }

    public function addCurso(Curso $curso): self
    {
        if (!$this->cursos->contains($curso)) {
            $this->cursos[] = $curso;
        }

        return $this;
    }

    public function removeCurso(Curso $curso): self
    {
        if ($this->cursos->contains($curso)) {
            $this->cursos->removeElement($curso);
        }

        return $this;
    }
}
