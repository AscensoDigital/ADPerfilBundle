<?php
/**
 * Created by PhpStorm.
 * User: asazo
 * Date: 03-07-15
 * Time: 1:45
 */

namespace AscensoDigital\PerfilBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ArchivoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo', $options['show_titulo'] ? 'text' : 'hidden', array(
                'label' => 'Título'
            ))
            ->add('file', 'file', array(
                'label' => 'Archivo'
            ))
            ->add('fecha_publicacion', 'datetime_hidden');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'show_titulo',
        ));
        $resolver->setDefaults(array(
            'data_class' => 'AscensoDigital\PerfilBundle\Entity\Archivo',
            'show_titulo' => false,
        ));
    }

    public function getName()
    {
        return 'archivo';
    }
}