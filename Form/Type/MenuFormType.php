<?php

namespace AscensoDigital\PerfilBundle\Form\Type;

use AscensoDigital\ComponentBundle\Form\Type\IconType;
use AscensoDigital\PerfilBundle\Entity\Color;
use AscensoDigital\PerfilBundle\Entity\Menu;
use AscensoDigital\PerfilBundle\Entity\Permiso;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('menuSuperior',EntityType::class,[
            'placeholder' => 'Homepage',
            'class' => Menu::class,
            'required' => false,
            'query_builder' => function (EntityRepository $er) {
                return $er->getQueryBuilderOrderNombre();
            }
        ])
            ->add('orden',IntegerType::class)
            ->add('nombre',TextType::class)
            ->add('descripcion',TextareaType::class)
            ->add('icono',IconType::class)
            ->add('color',EntityType::class,[
                'placeholder' => '',
                'class' => Color::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->getQueryBuilderOrderNombre();
                }
            ]);
        if($options['super_admin']) {
            $builder->add('route', TextType::class, [
                'required' => false
            ])
                ->add('permiso', EntityType::class, [
                    'placeholder' => '',
                    'class' => Permiso::class,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->getQueryBuilderOrderNombre();
                    }
                ])
                ->add('visible')
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['super_admin']);
    }

    public function getBlockPrefix()
    {
        return 'ad_perfil_menu_form_type';
    }
}
