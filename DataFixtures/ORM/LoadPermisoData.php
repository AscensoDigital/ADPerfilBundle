<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 03-04-16
 * Time: 19:36
 */

namespace AscensoDigital\PerfilBundle\DataFixtures\ORM;


use AscensoDigital\PerfilBundle\Entity\Permiso;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadPermisoData extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $mnCrear=$manager->getRepository('ADPerfilBundle:Permiso')->findOneBy(['nombre' => 'ad_perfil-menu-new']);
        if(!$mnCrear) {
            $mnCrear = new Permiso();
            $mnCrear->setNombre('ad_perfil-menu-new')
                ->setDescripcion('Crear Menu de PerfilBundle');
            $manager->persist($mnCrear);
        }
        $this->addReference('per-menu-new',$mnCrear);

        $mnEdit=$manager->getRepository('ADPerfilBundle:Permiso')->findOneBy(['nombre' => 'ad_perfil-menu-edit']);
        if(!$mnEdit) {
            $mnEdit = new Permiso();
            $mnEdit->setNombre('ad_perfil-menu-edit')
                ->setDescripcion('Editar Menu de PerfilBundle');
            $manager->persist($mnEdit);
        }
        $this->addReference('per-menu-edit',$mnEdit);

        $perCrear=$manager->getRepository('ADPerfilBundle:Permiso')->findOneBy(['nombre' => 'ad_perfil-per-new']);
        if(!$perCrear) {
            $perCrear = new Permiso();
            $perCrear->setNombre('ad_perfil-per-new')
                ->setDescripcion('Crear Permiso de PerfilBundle');
            $manager->persist($perCrear);
        }
        $this->addReference('per-per-new',$perCrear);

        $perEdit=$manager->getRepository('ADPerfilBundle:Permiso')->findOneBy(['nombre' => 'ad_perfil-per-edit']);
        if(!$perEdit) {
            $perEdit = new Permiso();
            $perEdit->setNombre('ad_perfil-per-edit')
                ->setDescripcion('Editar Permiso de PerfilBundle');
            $manager->persist($perEdit);
        }
        $this->addReference('per-per-edit',$perEdit);

        $perList=$manager->getRepository('ADPerfilBundle:Permiso')->findOneBy(['nombre' => 'ad_perfil-per-list']);
        if(!$perList) {
            $perList = new Permiso();
            $perList->setNombre('ad_perfil-per-list')
                ->setDescripcion('Listar Asignación de Permisos de PerfilBundle');
            $manager->persist($perList);
        }
        $this->addReference('per-per-list',$perList);

        $repIndex=$manager->getRepository('ADPerfilBundle:Permiso')->findOneBy(['nombre' => 'ad_perfil-mn-reporte']);
        if(!$repIndex) {
            $repIndex = new Permiso();
            $repIndex->setNombre('ad_perfil-mn-reporte')
                ->setDescripcion('Listado de Reportes de PerfilBundle');
            $manager->persist($repIndex);
        }
        $this->addReference('per-rep-list',$repIndex);

        $repCrear=$manager->getRepository('ADPerfilBundle:Permiso')->findOneBy(['nombre' => 'ad_perfil-rep-new']);
        if(!$repCrear) {
            $repCrear = new Permiso();
            $repCrear->setNombre('ad_perfil-rep-new')
                ->setDescripcion('Crear Reporte de PerfilBundle');
            $manager->persist($repCrear);
        }
        $this->addReference('per-rep-new',$repCrear);

        $repEdit=$manager->getRepository('ADPerfilBundle:Permiso')->findOneBy(['nombre' => 'ad_perfil-rep-edit']);
        if(!$repEdit) {
            $repEdit = new Permiso();
            $repEdit->setNombre('ad_perfil-rep-edit')
                ->setDescripcion('Editar Reporte de PerfilBundle');
            $manager->persist($repEdit);
        }
        $this->addReference('per-rep-edit',$repEdit);

        $repLoad=$manager->getRepository('ADPerfilBundle:Permiso')->findOneBy(['nombre' => 'ad_perfil-rep-load-estatico']);
        if(!$repLoad) {
            $repLoad = new Permiso();
            $repLoad->setNombre('ad_perfil-rep-load-estatico')
                ->setDescripcion('Cargar archivo estatico a reporte de PerfilBundle');
            $manager->persist($repLoad);
        }
        $this->addReference('per-rep-load',$repLoad);

        $repDownloadNombre=$manager->getRepository('ADPerfilBundle:Permiso')->findOneBy(['nombre' => 'ad_perfil-rep-download-nombre']);
        if(!$repDownloadNombre) {
            $repDownloadNombre = new Permiso();
            $repDownloadNombre->setNombre('ad_perfil-rep-download-nombre')
                ->setDescripcion('Descargar reporte con nombre de PerfilBundle');
            $manager->persist($repDownloadNombre);
        }
        $this->addReference('per-rep-download-nombre',$repDownloadNombre);

        $confIndex=$manager->getRepository('ADPerfilBundle:Permiso')->findOneBy(['nombre' => 'ad_perfil-mn-configuracion']);
        if(!$confIndex) {
            $confIndex = new Permiso();
            $confIndex->setNombre('ad_perfil-mn-configuracion')
                ->setDescripcion('Menu Configuración');
            $manager->persist($confIndex);
        }
        $this->addReference('per-config-index',$confIndex);

        $mapIndex=$manager->getRepository('ADPerfilBundle:Permiso')->findOneBy(['nombre' => 'ad_perfil-mn-mapa-sitio']);
        if(!$mapIndex) {
            $mapIndex = new Permiso();
            $mapIndex->setNombre('ad_perfil-mn-mapa-sitio')
                ->setDescripcion('Menu Mapa del Sitio');
            $manager->persist($mapIndex);
        }
        $this->addReference('per-mapa-sitio-index',$mapIndex);
        $manager->flush();
    }
}
