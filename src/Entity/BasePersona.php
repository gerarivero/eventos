<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


trait BasePersona
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
     * @ORM\Column(name="telefono", type="integer")
     */
    protected $telefono;

    /**
     * @var array
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    protected $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="dni", type="string", length=255)     
     */
    protected $dni;


    public function __toString()
    {
        return "$this->apellido $this->nombre";
    }
    /**
     * Constructor
     */
    public function __construct()
    {        
        $this->activo = true;
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

    public function setActivo(bool $activo): self
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

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }
}
