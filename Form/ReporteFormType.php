<?php

namespace AscensoDigital\PerfilBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReporteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('reporteSeccion')
            ->add('reporteCategoria')
            ->add('reporteCriterio','entity',[
                'required' => false
            ])
            ->add('permiso','entity',[
                'required' => false
            ])
            ->add('nombre')
            ->add('codigo')
            ->add('descripcion')
            ->add('route','text',[
                'required' => false
            ])
            ->add('orden')
            ->add('repositorio','text',[
                'required' => false
            ])
            ->add('metodo','text',[
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AscensoDigital\PerfilBundle\Entity\Reporte'
        ]);
    }

    public function getName()
    {
        return 'reporte';
    }
}
