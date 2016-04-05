<?php

namespace AscensoDigital\PerfilBundle\Entity;

use AscensoDigital\PerfilBundle\Model\UserInterface;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Archivo
 *
 * @ORM\Table(name="ad_perfil_archivo")
 * @ORM\Entity
 */
class Archivo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="archivo_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable=true)
     */
    protected $titulo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_publicacion", type="datetime", nullable=true)
     */
    protected $fechaPublicacion;

    /**
     * @var string
     *
     * @ORM\Column(name="ruta", type="string", length=255, nullable=true)
     */
    protected $ruta;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean", nullable=false)
     */
    protected $visible = false;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=200, nullable=true)
     */
    protected $mimeType;

    /**
     * @var UploadedFile $file
     * @Assert\NotBlank
     */
    protected $file;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="AscensoDigital\PerfilBundle\Model\UserInterface")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creador_id", referencedColumnName="id")
     * })
     */
    protected $creador;



    public function getArraySerialize(){
        $ret = array();
        $ret['titulo'] = $this->getTitulo();
        $ret['id'] = $this->getId();

        return $ret;
    }


    public function getExtensionOriginal() {
        if(is_null($this->file)) {
            return '';
        }
        $nombreOriginal=$this->getFile()->getClientOriginalName();
        $arr=explode('.',$nombreOriginal);
        return '.'.$arr[count($arr)-1];
    }

    public function getNombre(){
        $get_nombre = explode("/", $this->getRuta());
        return array_pop($get_nombre);
    }

    public function getPath() {
        return $this->getUploadRootDir() . DIRECTORY_SEPARATOR . $this->getRuta();
    }

    public function getUploadDir()
    {
        return 'uploads';
    }


    public function getUploadRootDir() {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * @param $directorio
     * @param $nombre
     * @param $file
     * @param bool|false $visible
     */
    public function saveFile($directorio, $nombre, $file, $visible = false)
    {
        $targetDir = $this->getUploadRootDir() . DIRECTORY_SEPARATOR . $directorio;
        $fs = new Filesystem();
        $nombreArray=explode('.',$nombre);
        $extension=array_pop($nombreArray);
        $slugify = new Slugify();
        $nombre=$slugify->slugify(implode('.',$nombreArray)).'.'.$extension;
        $archivo = $targetDir . DIRECTORY_SEPARATOR . $nombre;

        try {
            $fs->dumpFile($archivo, $file);
        } catch (IOException $e) {
            echo "Se ha producido un error al crear el archivo ".$e->getPath();
        }
        $this->setRuta($directorio . DIRECTORY_SEPARATOR . $nombre);
        $this->visible = $visible;
    }

    /**
     * Upload archivo
     */
    public function upload($directorio, $nombre, $visible = false)
    {
        if(null === $this->file) {
            return false;
        }

        $targetDir = $this->getUploadRootDir() . DIRECTORY_SEPARATOR . $directorio;
        $extension=$this->getExtensionOriginal();
        $nombreArray=explode('.',$nombre);
        if($nombreArray[count($nombreArray)-1]==$extension){
            array_pop($nombreArray);
        }
        $slugify = new Slugify();
        $nombre=$slugify->slugify(implode('.',$nombreArray)).'.'.$extension;
        $this->file->move($targetDir, $nombre);

        $this->setRuta($directorio . DIRECTORY_SEPARATOR . $nombre);
        $this->setMimeType($this->file->getClientMimeType());
        $this->visible = $visible;
        return $nombre;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     * @return Archivo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set fechaPublicacion
     *
     * @param \DateTime $fechaPublicacion
     * @return Archivo
     */
    public function setFechaPublicacion($fechaPublicacion)
    {
        $this->fechaPublicacion = $fechaPublicacion;

        return $this;
    }

    /**
     * Get fechaPublicacion
     *
     * @return \DateTime 
     */
    public function getFechaPublicacion()
    {
        return $this->fechaPublicacion;
    }

    /**
     * Set ruta
     *
     * @param string $ruta
     * @return Archivo
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }

    /**
     * Get ruta
     *
     * @return string 
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return Archivo
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean 
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return Archivo
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set creador
     *
     * @param UserInterface $creador
     * @return Archivo
     */
    public function setCreador(UserInterface $creador = null)
    {
        $this->creador = $creador;

        return $this;
    }

    /**
     * Get creador
     *
     * @return UserInterface
     */
    public function getCreador()
    {
        return $this->creador;
    }

    /**
     * Sets file
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        $this->setFechaPublicacion(new \DateTime());
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
}
