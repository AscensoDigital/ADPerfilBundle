<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 20-01-16
 * Time: 23:38
 */

namespace AscensoDigital\PerfilBundle\EventListener;


use AscensoDigital\PerfilBundle\Model\PerfilInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;

class AuthenticationListener
{
    private $sessionName;

    public function __construct($sessionName)
    {
        $this->sessionName=$sessionName;
    }

    public function onFormLogin(InteractiveLoginEvent $event) {
        $user=$event->getAuthenticationToken()->getUser();
        if(is_object($user)) {
            $perfils=$user->getPerfils();
            $this->setPerfilId($event->getRequest(), $perfils);
        }
    }

    public function onSwitchUser(SwitchUserEvent $event) {
        $request=$event->getRequest();
        $request->getSession()->remove('ut_id');
        $request->getSession()->remove('ad_perfil.perfil_multiple');
        $user=$event->getTargetUser();
        if(is_object($user)) {
            $perfils=$user->getPerfils();
            $this->setPerfilId($event->getRequest(), $perfils);
        }
    }

    private function setPerfilId(Request $request, $perfils) {
        $request->getSession()->set($this->sessionName,null);
        switch(count($perfils)) {
            case 0:
                $request->getSession()->remove('ad_perfil.perfil_multiple');
                break;
            case 1:
                $request->getSession()->set('ad_perfil.perfil_multiple',false);
                /** @var PerfilInterface $perfil */
                $perfil=array_pop($perfils);
                $request->getSession()->set($this->sessionName,$perfil->getId());
                break;
            default:
                $request->getSession()->set('ad_perfil.perfil_multiple',true);
                break;
        }
        $this->removeFiltros($request);
    }

    private function removeFiltros(Request $request) {
        foreach($request->getSession()->all() as $key_ses => $data) {
            if(strpos($key_ses,'filtros_')!==false) {
                $request->getSession()->remove($key_ses);
            }
        }
    }
}
