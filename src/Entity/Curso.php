<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Curso
 * @ORM\Entity(repositoryClass="App\Repository\CursoRepository")
 * @ORM\Table(name="curso")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Curso
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inscripcion", mappedBy="curso", orphanRemoval=false)
     */
    private $inscripciones;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $duracion;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $publicado;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clave;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaInicio;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaFin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaInicioInscripcion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaFinInscripcion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lugar;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $organizador;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coOrganizador;
    
    /**
     * @ORM\Column(type="text")
     */
    private $detalles;
    
    /**
     * @ORM\Column(type="text")
     */
    private $objetivos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cupo;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imagenPrincipal;
    
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="cursos_images", fileNameProperty="imagenPrincipal")
     *
     * @var File
     */    
    private $imageFile;
    
    /**
     * @ORM\OneToMany(targetEntity="Resource", mappedBy="curso", orphanRemoval=true, cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $materiales;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Docente", mappedBy="cursos")
     */
    private $docentes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plantilla", inversedBy="cursos")
     */
    private $plantilla;

    public function __construct()
    {
        $this->inscripciones = new ArrayCollection();
        $this->updatedAt = new \DateTime('now');
        $this->fechaInicio = new \DateTime('now');
        $this->fechaFin = new \DateTime('now');
        $this->fechaInicioInscripcion = new \DateTime('now');
        $this->fechaFinInscripcion = new \DateTime('now');
        $this->materiales = new ArrayCollection();
        $this->docentes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuracion(): ?string
    {
        return $this->duracion;
    }

    public function setDuracion(string $duracion): self
    {
        $this->duracion = $duracion;

        return $this;
    }

    public function getPublicado(): ?bool
    {
        return $this->publicado;
    }

    public function setPublicado(?bool $publicado): self
    {
        $this->publicado = $publicado;

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

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTimeInterface $fechaInicio): self
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(\DateTimeInterface $fechaFin): self
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    public function getFechaInicioInscripcion(): ?\DateTimeInterface
    {
        return $this->fechaInicioInscripcion;
    }

    public function setFechaInicioInscripcion(\DateTimeInterface $fechaInicioInscripcion): self
    {
        $this->fechaInicioInscripcion = $fechaInicioInscripcion;

        return $this;
    }

    public function getFechaFinInscripcion(): ?\DateTimeInterface
    {
        return $this->fechaFinInscripcion;
    }

    public function setFechaFinInscripcion(\DateTimeInterface $fechaFinInscripcion): self
    {
        $this->fechaFinInscripcion = $fechaFinInscripcion;

        return $this;
    }

    public function getLugar(): ?string
    {
        return $this->lugar;
    }

    public function setLugar(string $lugar): self
    {
        $this->lugar = $lugar;

        return $this;
    }

    public function getDetalles(): ?string
    {
        return $this->detalles;
    }

    public function setDetalles(string $detalles): self
    {
        $this->detalles = $detalles;

        return $this;
    }

    public function getObjetivos(): ?string
    {
        return $this->objetivos;
    }

    public function setObjetivos(string $objetivos): self
    {
        $this->objetivos = $objetivos;

        return $this;
    }

    public function getImagenPrincipal(): ?string
    {
        return $this->imagenPrincipal;
    }

    public function setImagenPrincipal(?string $imagenPrincipal): self
    {
        $this->imagenPrincipal = $imagenPrincipal;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
            $inscripcione->setCurso($this);
        }

        return $this;
    }

    public function removeInscripcione(Inscripcion $inscripcione): self
    {
        if ($this->inscripciones->contains($inscripcione)) {
            $this->inscripciones->removeElement($inscripcione);
            // set the owning side to null (unless already changed)
            if ($inscripcione->getCurso() === $this) {
                $inscripcione->setCurso(null);
            }
        }

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getCupo(): ?int
    {
        return $this->cupo;
    }

    public function setCupo(?int $cupo): self
    {
        $this->cupo = $cupo;

        return $this;
    }

    /**
     * @return Collection|Resource[]
     */
    public function getMateriales(): Collection
    {
        return $this->materiales;
    }

    public function addMateriale(Resource $materiale): self
    {
        if (!$this->materiales->contains($materiale)) {
            $this->materiales[] = $materiale;
            $materiale->setCurso($this);
        }

        return $this;
    }

    public function removeMateriale(Resource $materiale): self
    {
        if ($this->materiales->contains($materiale)) {
            $this->materiales->removeElement($materiale);
            // set the owning side to null (unless already changed)
            if ($materiale->getCurso() === $this) {
                $materiale->setCurso(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Docente[]
     */
    public function getDocentes(): Collection
    {
        return $this->docentes;
    }

    public function addDocente(Docente $docente): self
    {
        if (!$this->docentes->contains($docente)) {
            $this->docentes[] = $docente;
            $docente->addCurso($this);
        }

        return $this;
    }

    public function removeDocente(Docente $docente): self
    {
        if ($this->docentes->contains($docente)) {
            $this->docentes->removeElement($docente);
            $docente->removeCurso($this);
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

    public function getOrganizador(): ?string
    {
        return $this->organizador;
    }

    public function setOrganizador(?string $organizador): self
    {
        $this->organizador = $organizador;

        return $this;
    }

    public function getCoOrganizador(): ?string
    {
        return $this->coOrganizador;
    }

    public function setCoOrganizador(?string $coOrganizador): self
    {
        $this->coOrganizador = $coOrganizador;

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
            throw new Exception('No puede eliminar un curso con inscriptos.');
        }
    }
}
