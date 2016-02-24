<?php

namespace AscensoDigital\PerfilBundle\Form\Type;

use AscensoDigital\PerfilBundle\Entity\PerfilXPermiso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PerfilXPermisoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            /** @var PerfilXPermiso $pxp */
            $pxp = $event->getData();
            $form = $event->getForm();

            $form->add('acceso',null,array(
                'label' =>  $options['label_entity']=='Perfil' ? $pxp->getPerfil()->getLabel() : $pxp->getPermiso()->__toString(),
            ));
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array('label_entity'));
        $resolver->setDefaults(array(
            'data_class' => 'AscensoDigital\PerfilBundle\Entity\PerfilXPermiso',
            'label_entity' => 'Perfil'));
    }

    public function getBlockPrefix()
    {
        return 'perfil_x_permiso';
    }

    public function getName()
    {
        return 'perfil_x_permiso';
    }
}
