<?php

namespace AscensoDigital\PerfilBundle\Form\Type;

use AscensoDigital\PerfilBundle\Configuration\Configurator;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class FiltroFormType extends AbstractType
{
    /**
     * @var Configurator
     */
    private $configurator;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Router
     */
    private $router;
    
    private $route;

    public function __construct(Configurator $configurator, TokenStorageInterface $tokenStorage, Router $router) {
        $this->configurator=$configurator;
        $this->tokenStorage = $tokenStorage;
        $this->router= $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->route=$options['route'];
        $filtroActivos=$options['filtros'];

        $options['action'] = $this->router->generate($this->route,$options['route_params']);
        $options['attr']= array(
            'id' => 'ad_perfil-frm-filtros',
            'data-update' => $options['update'],
            'data-auto-filter' => $options['auto_filter'],
            'data-auto-llenado' => $options['auto_llenado']
        );

        //Se elimina data de cualquier filtro no solicitado
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function(FormEvent $event) use($filtroActivos){
                $data=$event->getData();
                foreach($data as $key => $filtro) {
                    if(!array_key_exists($key,$filtroActivos)) {
                        unset($data[$key]);
                    }
                }
                $event->setData($data);
            }
        );

        foreach ($filtroActivos as $keyFiltro => $filtro) {
            $filtroConf=$this->configurator->getFiltroConfiguration($keyFiltro);
            $options=$filtro+$filtroConf['options'];
            if(isset($filtroConf['query_builder_method'])){
                $method=$filtroConf['query_builder_method'];
                $options['query_builder']=function(EntityRepository $er) use ($method) {
                        return $er->$method();
                    };
            }
            $builder->add($keyFiltro, $filtroConf['type'],$options);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('filtros','route','update'));
        $resolver->setDefined(array('auto_filter','auto_llenado','perfil','route_params'));
        $resolver->setDefaults([
            'auto_filter' => true,
            'auto_llenado' => true,
            'perfil' => null,
            'route_params' => array()
        ]);
    }

    public function getBlockPrefix()
    {
        return 'ad_component_filtros_'.$this->route;
    }

    /**
     * SF <= 2.8
     *
     * @return string
     */
    public function getName()
    {
        return 'ad_component_filtros_'.$this->route;
    }
}
