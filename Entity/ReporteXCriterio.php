<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 01-04-16
 * Time: 14:18
 */

namespace AscensoDigital\PerfilBundle\Entity;

use AscensoDigital\PerfilBundle\Model\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reporte
 *
 * @ORM\Table(name="ad_perfil_reporte_x_criterio")
 * @ORM\Entity
 */
class ReporteXCriterio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="reporte_x_criterio_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="criterio_id", type="integer", nullable=true)
     */
    protected $criterioId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var Reporte
     *
     * @ORM\ManyToOne(targetEntity="Reporte", inversedBy="reporteXCriterios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reporte_id", referencedColumnName="id")
     * })
     */
    protected $reporte;

    /**
     * @var Archivo
     *
     * @ORM\ManyToOne(targetEntity="Archivo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="archivo_id", referencedColumnName="id")
     * })
     */
    protected $archivo;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="AscensoDigital\PerfilBundle\Model\UserInterface")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modificador_id", referencedColumnName="id")
     * })
     */
    protected $modificador;


    public function __toString()
    {
        return $this->getReporte().' | '.$this->getCriterioId();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $criterioId
     * @return ReporteXCriterio
     */
    public function setCriterioId($criterioId)
    {
        $this->criterioId = $criterioId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCriterioId()
    {
        return $this->criterioId;
    }

    /**
     * @param \DateTime $updatedAt
     * @return ReporteXCriterio
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param Reporte $reporte
     * @return ReporteXCriterio
     */
    public function setReporte($reporte)
    {
        $this->reporte = $reporte;
        return $this;
    }

    /**
     * @return Reporte
     */
    public function getReporte()
    {
        return $this->reporte;
    }

    /**
     * @param Archivo $archivo
     * @return ReporteXCriterio
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;
        return $this;
    }

    /**
     * @return Archivo
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * @param UserInterface $modificador
     * @return ReporteXCriterio
     */
    public function setModificador($modificador)
    {
        $this->modificador = $modificador;
        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getModificador()
    {
        return $this->modificador;
    }
}
