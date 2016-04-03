<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 01-04-16
 * Time: 19:01
 */

namespace AscensoDigital\PerfilBundle\Model;


use AscensoDigital\PerfilBundle\Entity\ReporteCriterio;
use AscensoDigital\PerfilBundle\Util\Dia;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class ReporteManager
{
    /**
     * @var EntityManager
     */
    private $em;
    private $perfil_id;

    public function __construct(Session $session, EntityManager $em, $sessionName)
    {
        $this->em = $em;
        $this->perfil_id = $session->get($sessionName,null);
    }

    public function getCriterioNombre(ReporteCriterio $reporteCriterio) {
        
    }

    public function getDataReportesForList(){
        $dat=$this->em->getRepository('ADPerfilBundle:Reporte')->findArrayByPerfil($this->perfil_id);
        $criterios=array();
        /** @var ReporteCriterio $reporteCriterio */
        foreach ($dat['criterio'] as $nombre => $reporteCriterio) {
            switch ($nombre){
                case 'periodo':
                    $criterios[$nombre]['data']=array(new Dia(0,'Completo'), new Dia(date('Y-m-d'), 'Diario'));
                    break;
                default:
                    $metodo=$reporteCriterio->getMetodo();
                    $criterios[$nombre]['data']=$this->em->getRepository($reporteCriterio->getRepositorio())->$metodo();
            }
            $criterios[$nombre]['titulo']=$reporteCriterio->getTitulo();
        }
        $dat['criterios']=$criterios;
        return $dat;
    }
}
