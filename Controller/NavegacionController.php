<?php

namespace AscensoDigital\PerfilBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NavegacionController extends Controller
{
    /**
     * @Route("/index", name="ad_perfil_homepage")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $menus=$em->getRepository('');
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'menus' => $menus,
        ]);
    }

    public function createAction(Request $request){

    }
}
