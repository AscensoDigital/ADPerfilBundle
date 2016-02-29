<?php

namespace AscensoDigital\PerfilBundle\Form\Type;

use AscensoDigital\PerfilBundle\Configuration\Configurator;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

    public function __construct(Configurator $configurator, TokenStorageInterface $tokenStorage, Router $router) {
        $this->configurator=$configurator;
        $this->tokenStorage = $tokenStorage;
        $this->router= $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $filtroActivos=$options['filtros'];

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
        $user=$this->tokenStorage->getToken()->getUser();
        $perfil=$options['perfil'];
        foreach ($filtroActivos as $keyFiltro => $filtro) {
            $filtroConf=$this->configurator->getFiltroConfiguration($keyFiltro);
            $options=$filtro+ $filtroConf['options'] + ['required' => false];
            if(isset($filtroConf['query_builder_method'])){
                $method=$filtroConf['query_builder_method'];
                if(true===$filtroConf['query_builder_perfil'] && true===$filtroConf['query_builder_user']) {
                    $options['query_builder']=function(EntityRepository $er) use ($method,$perfil,$user) {
                        return $er->$method($user,$perfil);
                    };
                }
                elseif(true===$filtroConf['query_builder_perfil']){
                    $options['query_builder']=function(EntityRepository $er) use ($method,$perfil) {
                        return $er->$method($perfil);
                    };
                }
                elseif(true===$filtroConf['query_builder_user']) {
                    $options['query_builder']=function(EntityRepository $er) use ($method,$user) {
                        return $er->$method($user);
                    };
                }
                else {
                    $options['query_builder']=function(EntityRepository $er) use ($method) {
                            return $er->$method();
                        };
                }
            }
            $builder->add($keyFiltro, $filtroConf['type'],$options);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars['action'] = $this->router->generate($options['route'],$options['route_params']);
        $view->vars['attr']= $view->vars['attr'] + array(
                'id' => 'ad_perfil-frm-filtros',
                'data-route' => $options['route'],
                'data-update' => $options['update'],
                'data-auto-filter' => $options['auto_filter'] ? 1 : 0,
                'data-auto-llenado' => $options['auto_llenado'] ? 1 : 0
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('filtros','route','update'));
        $resolver->setDefined(array('auto_filter','auto_llenado','perfil','route_params'));
        $resolver->setDefaults([
            'auto_filter' => true,
            'auto_llenado' => true,
            'perfil' => null,
            'route_params' => array(),
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'ad_perfil_filtros';
    }

    /**
     * SF <= 2.8
     *
     * @return string
     */
    public function getName()
    {
        return 'ad_perfil_filtros';
    }
}
