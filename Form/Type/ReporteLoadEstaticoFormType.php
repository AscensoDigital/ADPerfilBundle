<?php

namespace AscensoDigital\PerfilBundle\Form\Type;

use AscensoDigital\PerfilBundle\Entity\Reporte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReporteLoadEstaticoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('reporte','entity',[
            'class' => Reporte::class,
            'read_only' => true
        ])
            ->add('criterioId',ChoiceType::class, [
            'choices' => $options['criterio_choices']
        ])
            ->add('archivo', ArchivoType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('criterio_choices');
    }

    public function getName()
    {
        return 'reporte_load_estatico';
    }
}
