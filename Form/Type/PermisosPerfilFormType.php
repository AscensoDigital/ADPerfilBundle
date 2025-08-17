<?php
/**
 * Created by PhpStorm.
 * User: patito
 * Date: 09-07-15
 * Time: 11:16
 */

namespace AscensoDigital\PerfilBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class PermisosPerfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('perfilXPermisos',CollectionType::class,array(
                'label' => 'Asignación',
                'type'   => PerfilXPermisoType::class,
                'by_reference' => false,
                'options' => array('label_entity' => 'Permiso')
            ));
    }

    public function getBlockPrefix()
    {
        return 'ad_perfil_permisos_perfil';
    }

    public function getName()
    {
        return 'ad_perfil_permisos_perfil';
    }
}
