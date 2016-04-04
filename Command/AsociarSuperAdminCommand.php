<?php

namespace AscensoDigital\PerfilBundle\Command;

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

    }
}
