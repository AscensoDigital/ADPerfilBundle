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
                'entry_type'   => PerfilXPermisoType::class,
                'by_reference' => false,
                'options' => array('label_entity' => 'Permiso')
            ));
    }

    public function getName()
    {
        return 'permisos_perfil';
    }
}
