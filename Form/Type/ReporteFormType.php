<?php

namespace AscensoDigital\PerfilBundle\Form\Type;

use AscensoDigital\PerfilBundle\Entity\Permiso;
use AscensoDigital\PerfilBundle\Entity\ReporteCriterio;
use Doctrine\ORM\EntityRepository;
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
                'required' => false,
                'class' => ReporteCriterio::class
            ])
            ->add('permiso','entity',[
                'required' => false,
                'class' => Permiso::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->getQueryBuilderOrderNombre();
                },
                'attr' => ['class' => 'select2']
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
