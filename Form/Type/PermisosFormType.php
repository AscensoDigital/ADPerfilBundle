<?php
/**
 * Created by PhpStorm.
 * User: patito
 * Date: 09-07-15
 * Time: 11:16
 */

namespace AscensoDigital\PerfilBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PermisosFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('nombre', 'text')
            ->add('descripcion', 'text', array(
                'label'=>'Descripción'
            ))
            ->add('perfilXPermisos','collection',array(
                'label' => 'Asignación',
                'type'   => PerfilXPermisoType::class,
                'by_reference' => false,
            ));
    }

    public function getName()
    {
        return 'permisos';
    }
}