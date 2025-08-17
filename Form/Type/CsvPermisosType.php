<?php

namespace AscensoDigital\PerfilBundle\Form\Type;

use AscensoDigital\PerfilBundle\Util\CsvPermisos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CsvPermisosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, array(
                'label' => 'Csv Permisos'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', CsvPermisos::class);
    }

    public function getBlockPrefix()
    {
        return 'adperfil_bundle_csv_permisos_type';
    }
}
