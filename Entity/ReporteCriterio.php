<?php

namespace AscensoDigital\PerfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReporteCriterio
 *
 * @ORM\Table(name="ad_perfil_reporte_criterio")
 * @ORM\Entity
 */
class ReporteCriterio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="reporte_criterio_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=20, nullable=true)
     */
    protected $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="repositorio", type="string", length=100, nullable=true)
     */
    protected $repositorio;

    /**
     * @var string
     *
     * @ORM\Column(name="metodo", type="string", length=100, nullable=true)
     */
    protected $metodo;


}
