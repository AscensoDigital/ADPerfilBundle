<?php

namespace AscensoDigital\PerfilBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CsvPermisosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'label' => 'Csv Permisos'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', 'AscensoDigital\PerfilBundle\Util\CsvPermisos');
    }

    public function getBlockPrefix()
    {
        return 'adperfil_bundle_csv_permisos_type';
    }
}
