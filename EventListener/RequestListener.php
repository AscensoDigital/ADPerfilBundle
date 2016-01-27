<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 26-01-16
 * Time: 22:00
 */

namespace AscensoDigital\PerfilBundle\EventListener;


use AscensoDigital\PerfilBundle\Model\MenuManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    private $menuManager;

    public function __construct(MenuManager $menuManager) {
        $this->menuManager=$menuManager;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->isMasterRequest()) {
            $route=$event->getRequest()->attributes->get('_route');
            if($route!='ad_perfil_menu') {
                $this->menuManager->setMenuActualSinceRoute($route);
            }
        }
    }
}