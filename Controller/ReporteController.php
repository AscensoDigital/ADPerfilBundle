<?php

namespace AscensoDigital\PerfilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReporteController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }
}
