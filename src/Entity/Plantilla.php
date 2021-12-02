<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Plantilla
 *
 * @ORM\Table(name="plantilla")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Plantilla
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Assert\File(maxSize="5M")
     * @Vich\UploadableField(mapping="cursos_images", fileNameProperty="path")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     * @Assert\NotNull
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable=true)
     */
    private $titulo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tituloSize;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tituloMargin;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mostrarDni;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mostrarParticipacion;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $DniSize;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $DniMT;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $DniML;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $DniParticipacionSize;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $DniParticipacionMT;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $DniParticipacionML;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreMT;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreSize;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cursoSize;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cursoMargin;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mostrarDuracion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duracionMT;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duracionML;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duracionSize;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Qr", inversedBy="plantillas")
     */
    private $qr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qrMT;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qrML;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qrSize;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mostrarAsistencia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asistenciaMT;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asistenciaML;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $asistenciaSize;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codigoMT;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codigoML;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codigoSize;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Curso", mappedBy="plantilla")
     */
    private $cursos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Congreso", mappedBy="plantilla")
     */
    private $congresos;

    /**
     * @var string
     *
     * @ORM\Column(name="font_family", type="string", length=255, nullable=true)
     */
    private $fontFamily;

    /**
     * @var string
     *
     * @ORM\Column(name="font_style", type="string", length=255, nullable=true)
     */
    private $fontStyle;

    /**
     * @var string
     *
     * @ORM\Column(name="font_weight", type="string", length=255, nullable=true)
     */
    private $fontWeight;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
        $this->cursos = new ArrayCollection();
        $this->congresos = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
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

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getNombreMT(): ?int
    {
        return $this->nombreMT;
    }

    public function setNombreMT(?int $nombreMT): self
    {
        $this->nombreMT = $nombreMT;

        return $this;
    }

    public function getNombreSize(): ?int
    {
        return $this->nombreSize;
    }

    public function setNombreSize(?int $nombreSize): self
    {
        $this->nombreSize = $nombreSize;

        return $this;
    }

    public function getMostrarDuracion(): ?bool
    {
        return $this->mostrarDuracion;
    }

    public function setMostrarDuracion(?bool $mostrarDuracion): self
    {
        $this->mostrarDuracion = $mostrarDuracion;

        return $this;
    }

    public function getQr(): ?Qr
    {
        return $this->qr;
    }

    public function setQr(?Qr $qr): self
    {
        $this->qr = $qr;

        return $this;
    }

    public function getMostrarAsistencia(): ?bool
    {
        return $this->mostrarAsistencia;
    }

    public function setMostrarAsistencia(?bool $mostrarAsistencia): self
    {
        $this->mostrarAsistencia = $mostrarAsistencia;

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
            $curso->setPlantilla($this);
        }

        return $this;
    }

    public function removeCurso(Curso $curso): self
    {
        if ($this->cursos->contains($curso)) {
            $this->cursos->removeElement($curso);
            // set the owning side to null (unless already changed)
            if ($curso->getPlantilla() === $this) {
                $curso->setPlantilla(null);
            }
        }

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(?string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getTituloSize(): ?int
    {
        return $this->tituloSize;
    }

    public function setTituloSize(?int $tituloSize): self
    {
        $this->tituloSize = $tituloSize;

        return $this;
    }

    public function getTituloMargin(): ?int
    {
        return $this->tituloMargin;
    }

    public function setTituloMargin(?int $tituloMargin): self
    {
        $this->tituloMargin = $tituloMargin;

        return $this;
    }

    public function getMostrarDni(): ?bool
    {
        return $this->mostrarDni;
    }

    public function setMostrarDni(?bool $mostrarDni): self
    {
        $this->mostrarDni = $mostrarDni;

        return $this;
    }

    public function getMostrarParticipacion(): ?bool
    {
        return $this->mostrarParticipacion;
    }

    public function setMostrarParticipacion(?bool $mostrarParticipacion): self
    {
        $this->mostrarParticipacion = $mostrarParticipacion;

        return $this;
    }

    public function getDniParticipacionSize(): ?int
    {
        return $this->DniParticipacionSize;
    }

    public function setDniParticipacionSize(?int $DniParticipacionSize): self
    {
        $this->DniParticipacionSize = $DniParticipacionSize;

        return $this;
    }

    public function getQrMT(): ?int
    {
        return $this->qrMT;
    }

    public function setQrMT(?int $qrMT): self
    {
        $this->qrMT = $qrMT;

        return $this;
    }

    public function getQrML(): ?int
    {
        return $this->qrML;
    }

    public function setQrML(?int $qrML): self
    {
        $this->qrML = $qrML;

        return $this;
    }

    public function getAsistenciaMT(): ?int
    {
        return $this->asistenciaMT;
    }

    public function setAsistenciaMT(?int $asistenciaMT): self
    {
        $this->asistenciaMT = $asistenciaMT;

        return $this;
    }

    public function getAsistenciaML(): ?int
    {
        return $this->asistenciaML;
    }

    public function setAsistenciaML(?int $asistenciaML): self
    {
        $this->asistenciaML = $asistenciaML;

        return $this;
    }

    public function getAsistenciaSize(): ?int
    {
        return $this->asistenciaSize;
    }

    public function setAsistenciaSize(?int $asistenciaSize): self
    {
        $this->asistenciaSize = $asistenciaSize;

        return $this;
    }

    public function getQrSize(): ?int
    {
        return $this->qrSize;
    }

    public function setQrSize(?int $qrSize): self
    {
        $this->qrSize = $qrSize;

        return $this;
    }

    public function getDniParticipacionMT(): ?int
    {
        return $this->DniParticipacionMT;
    }

    public function setDniParticipacionMT(?int $DniParticipacionMT): self
    {
        $this->DniParticipacionMT = $DniParticipacionMT;

        return $this;
    }

    public function getCursoSize(): ?int
    {
        return $this->cursoSize;
    }

    public function setCursoSize(?int $cursoSize): self
    {
        $this->cursoSize = $cursoSize;

        return $this;
    }

    public function getCursoMargin(): ?int
    {
        return $this->cursoMargin;
    }

    public function setCursoMargin(?int $cursoMargin): self
    {
        $this->cursoMargin = $cursoMargin;

        return $this;
    }

    /**
     * @return Collection|Congreso[]
     */
    public function getCongresos(): Collection
    {
        return $this->congresos;
    }

    public function addCongreso(Congreso $congreso): self
    {
        if (!$this->congresos->contains($congreso)) {
            $this->congresos[] = $congreso;
            $congreso->setPlantilla($this);
        }

        return $this;
    }

    public function removeCongreso(Congreso $congreso): self
    {
        if ($this->congresos->contains($congreso)) {
            $this->congresos->removeElement($congreso);
            // set the owning side to null (unless already changed)
            if ($congreso->getPlantilla() === $this) {
                $congreso->setPlantilla(null);
            }
        }

        return $this;
    }

    public function getDniParticipacionML(): ?int
    {
        return $this->DniParticipacionML;
    }

    public function setDniParticipacionML(?int $DniParticipacionML): self
    {
        $this->DniParticipacionML = $DniParticipacionML;

        return $this;
    }

    public function getCodigoMT(): ?int
    {
        return $this->codigoMT;
    }

    public function setCodigoMT(?int $codigoMT): self
    {
        $this->codigoMT = $codigoMT;

        return $this;
    }

    public function getCodigoML(): ?int
    {
        return $this->codigoML;
    }

    public function setCodigoML(?int $codigoML): self
    {
        $this->codigoML = $codigoML;

        return $this;
    }

    public function getCodigoSize(): ?int
    {
        return $this->codigoSize;
    }

    public function setCodigoSize(?int $codigoSize): self
    {
        $this->codigoSize = $codigoSize;

        return $this;
    }

    public function getDniSize(): ?int
    {
        return $this->DniSize;
    }

    public function setDniSize(?int $DniSize): self
    {
        $this->DniSize = $DniSize;

        return $this;
    }

    public function getDniMT(): ?int
    {
        return $this->DniMT;
    }

    public function setDniMT(?int $DniMT): self
    {
        $this->DniMT = $DniMT;

        return $this;
    }

    public function getDniML(): ?int
    {
        return $this->DniML;
    }

    public function setDniML(?int $DniML): self
    {
        $this->DniML = $DniML;

        return $this;
    }

    public function getDuracionMT(): ?int
    {
        return $this->duracionMT;
    }

    public function setDuracionMT(?int $duracionMT): self
    {
        $this->duracionMT = $duracionMT;

        return $this;
    }

    public function getDuracionML(): ?int
    {
        return $this->duracionML;
    }

    public function setDuracionML(?int $duracionML): self
    {
        $this->duracionML = $duracionML;

        return $this;
    }

    public function getDuracionSize(): ?int
    {
        return $this->duracionSize;
    }

    public function setDuracionSize(?int $duracionSize): self
    {
        $this->duracionSize = $duracionSize;

        return $this;
    }

    public function getFont(): ?string
    {
        return $this->font;
    }

    public function setFont(?string $font): self
    {
        $this->font = $font;

        return $this;
    }

    public function getFontFamily(): ?string
    {
        return $this->fontFamily;
    }

    public function setFontFamily(?string $fontFamily): self
    {
        $this->fontFamily = $fontFamily;

        return $this;
    }

    public function getFontStyle(): ?string
    {
        return $this->fontStyle;
    }

    public function setFontStyle(?string $fontStyle): self
    {
        $this->fontStyle = $fontStyle;

        return $this;
    }

    public function getFontWeight(): ?string
    {
        return $this->fontWeight;
    }

    public function setFontWeight(?string $fontWeight): self
    {
        $this->fontWeight = $fontWeight;

        return $this;
    }
}
