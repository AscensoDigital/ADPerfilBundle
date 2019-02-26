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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ArchivoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $optionsFecha = $options['show_publicacion'] ? ['widget' => 'single_text'] : [];

        $builder
            ->add('titulo', $options['show_titulo'] ? TextType::class : HiddenType::class, [
                'label' => 'Título'
            ])
            ->add('file', FileType::class, [
                'label' => 'Archivo'
            ])
            ->add('fecha_publicacion', $options['show_publicacion'] ? DateTimeType::class : DateTimeHiddenType::class, $optionsFecha);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'show_titulo',
            'show_publicacion'
        ));
        $resolver->setDefaults(array(
            'data_class' => Archivo::class,
            'show_titulo' => false,
            'show_publicacion' => false
        ));
    }
}
