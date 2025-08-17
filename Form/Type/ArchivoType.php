<?php
/**
 * Created by PhpStorm.
 * User: asazo
 * Date: 03-07-15
 * Time: 1:45
 */

namespace AscensoDigital\PerfilBundle\Form\Type;
use AscensoDigital\ComponentBundle\Form\Type\DateTimeHiddenType;
use AscensoDigital\PerfilBundle\Entity\Archivo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ArchivoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo', $options['show_titulo'] ? TextType::class : HiddenType::class, array(
                'label' => 'Título'
            ))
            ->add('file', FileType::class, array(
                'label' => 'Archivo'
            ))
            ->add('fecha_publicacion', DateTimeHiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'show_titulo',
        ));
        $resolver->setDefaults(array(
            'data_class' => Archivo::class,
            'show_titulo' => false,
        ));
    }

    public function getBlockPrefix()
    {
        return 'ad_perfil_archivo';
    }

    public function getName()
    {
        return 'ad_perfil_archivo';
    }
}
