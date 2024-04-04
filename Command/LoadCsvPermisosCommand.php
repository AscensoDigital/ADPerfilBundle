<?php

namespace AscensoDigital\PerfilBundle\Command;

use AscensoDigital\PerfilBundle\Entity\PerfilXPermiso;
use AscensoDigital\PerfilBundle\Entity\Permiso;
use AscensoDigital\PerfilBundle\Model\PerfilInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class LoadCsvPermisosCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('adperfil:permiso:load')
            ->setDescription('carga archivo csv de permisos por perfil')
            ->addArgument('archivo_csv',InputArgument::REQUIRED,'ruta al archivo csv de los permisos por perfil');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fs = new Filesystem();
        if (!$fs->exists($input->getArgument('archivo_csv'))) {
            $output->writeln('No existe el archivo: ' . $input->getArgument('archivo_csv'));
            return true;
        }


        if (($gestor = fopen($input->getArgument('archivo_csv'), "r")) !== FALSE) {
            $em = $this->getContainer()->get('doctrine')->getManager();

            $readEncabezado = true;
            $perfils = $this->getContainer()->get('ad_perfil.perfil_manager')->findAllOrderRole();
            $permisos = $em->getRepository('ADPerfilBundle:Permiso')->findArrayAllByNombre();
            $arrPerfilXPermisos = $em->getRepository('ADPerfilBundle:PerfilXPermiso')->findAllArray();
            // dump($arrPerfilXPermisos);

            /** @var PerfilInterface[] $arrPerfilSlugs */
            $arrPerfilSlugs = [];
            $countPermisos = 0;
            while (($datos = fgetcsv($gestor, 0, ";")) !== FALSE) {
                // $numero = count($datos);
                // dump($datos);
                if ($readEncabezado && !in_array($datos[0], ["sep=", "sep=;"])) {
                    foreach ($datos as $key => $perfilSlug) {
                        if ($key > 1) {
                            /** @var PerfilInterface $perfil */
                            foreach ($perfils as $perfil) {
                                if ($perfil->getSlug() == $perfilSlug) {
                                    $arrPerfilSlugs[$key] = $perfil;
                                }
                            }
                        }
                    }
                    $readEncabezado = false;
                    continue;
                }

                /** @var Permiso $permiso */
                $permiso = isset($permisos[$datos[0]]) ? $permisos[$datos[0]] : false;

                if ($permiso) {
                    // $output->writeln('permisoNombre: '.$permiso->getNombre());
                    foreach ($datos as $key => $acceso) {
                        // $output->writeln('key: '.$key);
                        $boolAcceso = $acceso == 1;
                        // $output->writeln('boolAcceso: '.$boolAcceso);
                        // $output->writeln("isset arrPerfilSlugs[key]: ".isset($arrPerfilSlugs[$key]));
                        if (isset($arrPerfilSlugs[$key])) {
                            // $output->writeln("arrPerfilSlug[key]: ".$arrPerfilSlugs[$key]->getSlug());

                            if (isset($arrPerfilXPermisos[$permiso->getNombre()][$arrPerfilSlugs[$key]->getSlug()])) {
                                // $output->writeln("arrPerfilXPermisos[permiso->getNombre()][arrPerfilSlugs[key]->getSlug()]: ".$arrPerfilXPermisos[$permiso->getNombre()][$arrPerfilSlugs[$key]->getSlug()]);

                                if ($arrPerfilXPermisos[$permiso->getNombre()][$arrPerfilSlugs[$key]->getSlug()] != $boolAcceso) {
                                    /** @var PerfilXPermiso $pxp */
                                    $pxp = $em->getRepository('ADPerfilBundle:PerfilXPermiso')->findOneByPermisoNombrePerfilSlug($permiso->getNombre(), $arrPerfilSlugs[$key]->getSlug());
                                    $pxp->setAcceso($boolAcceso);
                                    $em->persist($pxp);
                                    $countPermisos++;
                                    $output->writeln("Actualizacion permiso '".$permiso->getNombre()."' para '".$arrPerfilSlugs[$key]->getSlug()." a: ".intval($boolAcceso,2));
                                }
                            } elseif ($boolAcceso) {
                                $pxp = new PerfilXPermiso();
                                $pxp->setPerfil($arrPerfilSlugs[$key])
                                    ->setPermiso($permiso)
                                    ->setAcceso($boolAcceso);
                                $em->persist($pxp);
                                $countPermisos++;
                                $output->writeln("Creación permiso '".$permiso->getNombre()."' para '".$arrPerfilSlugs[$key]->getSlug()." a: ".intval($boolAcceso,2));
                            }
                        }
                    }
                }
                if ($countPermisos % 100 == 0) {
                    $em->flush();
                }
            }
            $em->flush();
            $output->writeln("Se registro correctamente $countPermisos permisos.");
            return 0;
        } else {
            $output->writeln('No se pudo abrir el archivo ' . $input->getArgument('archivo_csv'));
        }

        return 1;
    }
}
