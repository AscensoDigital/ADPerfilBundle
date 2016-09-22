<?php

/*
 * This file is part of the EasyAdminBundle.
 *
 * (c) Javier Eguiluz <javier.eguiluz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AscensoDigital\PerfilBundle\Twig;

use AscensoDigital\PerfilBundle\Configuration\Configurator;

/**
 * Defines the filters and functions used to render the bundle's templates.
 *
 * @author Claudio Corvalan <claudio.corvalan@ennea.cl>
 */
class ADPerfilTwigExtension extends \Twig_Extension
{
    private $configurator;

    public function __construct(Configurator $configurator)
    {
        $this->configurator = $configurator;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('ad_perfil_get_config', array($this, 'getConfig')),
            new \Twig_SimpleFunction('ad_perfil_get_icon_path', array($this, 'getIconPath')),
            new \Twig_SimpleFunction('ad_perfil_get_icon_alt', array($this, 'getIconAlt')),
            new \Twig_SimpleFunction('ad_perfil_get_homepage_title', array($this, 'getHomepageTitle'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('ad_perfil_get_homepage_route', array($this, 'getHomepageRoute')),
            new \Twig_SimpleFunction('ad_perfil_get_homepage_subtitle', array($this, 'getHomepageSubtitle'), array('is_safe' => array('html'))),
        );
    }

    public function getConfig($key=null)
    {
        return $this->configurator->getConfiguration($key);
    }

    public function getIconPath() {
        $iconPath=$this->configurator->getConfiguration('icon_path');
        if(empty($iconPath)) {
            throw new \LogicException(sprintf("No se encuentra definido el path del icono solicitado"));
        }
        return $iconPath;
    }

    public function getIconAlt() {
        $iconAlt=$this->configurator->getConfiguration('icon_alt');
        if(empty($iconAlt)) {
            throw new \LogicException(sprintf("No se encuentra definido el alt del icono solicitado"));
        }
        return $iconAlt;
    }
    
    public function getHomepageTitle($header=false) {
        $title=$this->configurator->getNavegacionConfiguration('homepage_title');
        if(empty($title)) {
            throw new \LogicException(sprintf("No se encuentra definido el titulo de la aplicación"));
        }
        return true===$header ? strip_tags($title) : $title;
    }

    public function getHomepageRoute() {
        $route=$this->configurator->getNavegacionConfiguration('homepage_route');
        if(empty($route)) {
            throw new \LogicException(sprintf("No se encuentra definido la ruta del homepage"));
        }
        return $route;
    }

    public function getHomepageSubtitle($header=false) {
        $subtitle=$this->configurator->getNavegacionConfiguration('homepage_subtitle');
        if(empty($subtitle)) {
            throw new \LogicException(sprintf("No se encuentra definido el subtitulo de la aplicación"));
        }
        return true===$header ? strip_tags($subtitle) : $subtitle;
    }

    public function getName()
    {
        return 'ad_perfil_extension';
    }
}
