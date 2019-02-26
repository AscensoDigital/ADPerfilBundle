<?php

namespace AscensoDigital\PerfilBundle\Form\Type;

use AscensoDigital\PerfilBundle\Entity\Permiso;
use AscensoDigital\PerfilBundle\Entity\ReporteCriterio;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReporteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('reporteSeccion')
            ->add('reporteCategoria')
            ->add('reporteCriterio',EntityType::class,[
                'required' => false,
                'class' => ReporteCriterio::class
            ])
            ->add('permiso',EntityType::class,[
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
            ->add('route',TextType::class,[
                'required' => false
            ])
            ->add('orden', NumberType::class)
            ->add('manager', TextType::class, [
                'required' => false
            ])
            ->add('repositorio',TextType::class,[
                'required' => false
            ])
            ->add('metodo',TextType::class,[
                'required' => false
            ])
            ->add('sql',TextareaType::class, [
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
