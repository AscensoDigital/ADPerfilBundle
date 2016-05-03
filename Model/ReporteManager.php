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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReporteManager
{
    /**
     * @var EntityManager
     */
    private $em;
    private $perfil_id;
    /**
     * @var PerfilManager
     */
    private $perfilManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(Session $session, EntityManager $em, $sessionName, PerfilManager $perfilManager, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->perfil_id = $session->get($sessionName,null);
        $this->perfilManager= $perfilManager;
        $this->tokenStorage=$tokenStorage;
    }

    public function getCriterioChoices(ReporteCriterio $reporteCriterio = null) {
        if(is_null($reporteCriterio)) {
            return array();
        }
        switch ($reporteCriterio->getNombre()){
            case 'periodo':
                return array(new Dia(0,'Completo'), new Dia(date('Y-m-d'), 'Diario'));
            default:
                $metodo=is_null($reporteCriterio->getMetodo()) ? 'findAll' : $reporteCriterio->getMetodo();
                if(is_null($reporteCriterio->getManager())) {
                    if ($reporteCriterio->isIncludeUser() && $reporteCriterio->isIncludePerfil()) {
                        $user = $this->tokenStorage->getToken()->getUser();
                        $perfil = $this->perfilManager->find($this->perfil_id);
                        $options = $this->em->getRepository($reporteCriterio->getRepositorio())->$metodo($user, $perfil);
                    } elseif ($reporteCriterio->isIncludeUser()) {
                        $user = $this->tokenStorage->getToken()->getUser();
                        $options = $this->em->getRepository($reporteCriterio->getRepositorio())->$metodo($user);
                    } elseif ($reporteCriterio->isIncludePerfil()) {
                        $perfil = $this->perfilManager->find($this->perfil_id);
                        $options = $this->em->getRepository($reporteCriterio->getRepositorio())->$metodo($perfil);
                    } else {
                        $options = $this->em->getRepository($reporteCriterio->getRepositorio())->$metodo();
                    }
                }
                else{
                    if ($reporteCriterio->isIncludeUser() && $reporteCriterio->isIncludePerfil()) {
                        $user = $this->tokenStorage->getToken()->getUser();
                        $perfil = $this->perfilManager->find($this->perfil_id);
                        $options = $this->em->getRepository($reporteCriterio->getRepositorio(),$reporteCriterio->getManager())->$metodo($user, $perfil);
                    } elseif ($reporteCriterio->isIncludeUser()) {
                        $user = $this->tokenStorage->getToken()->getUser();
                        $options = $this->em->getRepository($reporteCriterio->getRepositorio(),$reporteCriterio->getManager())->$metodo($user);
                    } elseif ($reporteCriterio->isIncludePerfil()) {
                        $perfil = $this->perfilManager->find($this->perfil_id);
                        $options = $this->em->getRepository($reporteCriterio->getRepositorio(),$reporteCriterio->getManager())->$metodo($perfil);
                    } else {
                        $options = $this->em->getRepository($reporteCriterio->getRepositorio(),$reporteCriterio->getManager())->$metodo();
                    }
                }
                $ret=array();
                foreach ($options as $option) {
                    $ret[$option->getId()]=$option;
                }
                return $ret;
        }
    }

    public function getCriterioNombre(ReporteCriterio $reporteCriterio = null, $valor = null) {
        if(is_null($reporteCriterio) || is_null($valor)) {
            return '';
        }
        switch ($reporteCriterio->getNombre()) {
            case 'periodo':
                return $valor==0 ? 'Completo' : 'Diario';
            default:
                if(is_null($reporteCriterio->getManager())) {
                    $criterio = $this->em->getRepository($reporteCriterio->getRepositorio())->find($valor);
                }
                else {
                    $criterio = $this->em->getRepository($reporteCriterio->getRepositorio(),$reporteCriterio->getManager())->find($valor);
                }
                return $criterio ? $criterio->__toString() : '';
        }
    }

    public function getDataReportesForList(){
        $dat=$this->em->getRepository('ADPerfilBundle:Reporte')->findArrayByPerfil($this->perfil_id);
        $criterios=array();
        /** @var ReporteCriterio $reporteCriterio */
        foreach ($dat['criterios'] as $nombre => $reporteCriterio) {
            $criterios[$nombre]['data']= $this->getCriterioChoices($reporteCriterio);
            $criterios[$nombre]['titulo']=$reporteCriterio->getTitulo();
        }
        $dat['criterios']=$criterios;
        return $dat;
    }
}
