<?php

namespace AscensoDigital\PerfilBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('menuSuperior','entity',[
            'placeholder' => 'Homepage',
            'class' => 'AscensoDigital\PerfilBundle\Entity\Menu',
            'required' => false
        ])
            ->add('orden','integer')
            ->add('nombre','text')
            ->add('slug','text')
            ->add('descripcion','textarea')
            ->add('icono','text')
            ->add('color','entity',[
                'placeholder' => '',
                'class' => 'AscensoDigital\PerfilBundle\Entity\Color'
            ])
            ->add('route','text',[
                'required' => false
            ])
            ->add('permiso','entity',[
                'placeholder' => '',
                'class' => 'AscensoDigital\PerfilBundle\Entity\Permiso',
                'required' => false,
                'query_builder' => function(EntityRepository $er) {
                    return $er->getQueryBuilderOrderDescripcion();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'ad_perfil_menu_form_type';
    }
}
