<?php

namespace AscensoDigital\PerfilBundle\Command;

use AscensoDigital\PerfilBundle\Entity\PerfilXPermiso;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AsociarSuperAdminCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('adperfil:permiso:super-admin')
            ->setDescription('Asocia los permisos para configurar el sistema al perfil del argumento')
            ->addArgument('perfil_id',InputArgument::REQUIRED,'Id del perfil que podrá configurar el sistema');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $perfilManager=$this->getContainer()->get('ad_perfil.perfil_manager');
        $perfil=$perfilManager->find($input->getArgument('perfil_id'));
        if(is_null($perfil)){
            throw new \RuntimeException('El perfil ingresado no esta registrado en nuestra base de datos');
        }
        $em=$this->getContainer()->get('doctrine')->getManager();
        $permisos=$em->getRepository('ADPerfilBundle:Permiso')->findByNombreParcial('ad_perfil-');
        foreach ($permisos as $permiso) {
            /** @var PerfilXPermiso $pxp */
            $pxp=$em->getRepository('ADPerfilBundle:PerfilXPermiso')->findOneBy(['perfil' => $perfil->getId(), 'permiso' => $permiso->getId()]);
            if(!$pxp){
                $pxp= new PerfilXPermiso();
                $pxp->setPermiso($permiso)
                    ->setPerfil($perfil)
                    ->setAcceso(true);
            }
            else {
                $pxp->setAcceso(true);
            }
            $em->persist($pxp);
        }
        $em->flush();
    }
}
