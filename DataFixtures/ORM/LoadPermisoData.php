<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 03-04-16
 * Time: 19:36
 */

namespace AscensoDigital\PerfilBundle\DataFixtures\ORM;


use AscensoDigital\PerfilBundle\Entity\Permiso;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPermisoData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $mnCrear= new Permiso();
        $mnCrear->setNombre('ad_perfil-menu-new')
            ->setDescripcion('Crear Menu de PerfilBundle');
        $manager->persist($mnCrear);
        $this->addReference('per-menu-new',$mnCrear);

        $mnEdit= new Permiso();
        $mnEdit->setNombre('ad_perfil-menu-edit')
            ->setDescripcion('Editar Menu de PerfilBundle');
        $manager->persist($mnEdit);
        $this->addReference('per-menu-edit',$mnEdit);
        
        $perCrear= new Permiso();
        $perCrear->setNombre('ad_perfil-per-new')
            ->setDescripcion('Crear Permiso de PerfilBundle');
        $manager->persist($perCrear);
        $this->addReference('per-per-new',$perCrear);

        $perEdit= new Permiso();
        $perEdit->setNombre('ad_perfil-per-edit')
            ->setDescripcion('Editar Permiso de PerfilBundle');
        $manager->persist($perEdit);
        $this->addReference('per-per-edit',$perEdit);

        $perList= new Permiso();
        $perList->setNombre('ad_perfil-per-list')
            ->setDescripcion('Listar Asignación de Permisos de PerfilBundle');
        $manager->persist($perList);
        $this->addReference('per-per-list',$perList);

        $repIndex= new Permiso();
        $repIndex->setNombre('ad_perfil-mn-reporte')
            ->setDescripcion('Listado de Reportes de PerfilBundle');
        $manager->persist($repIndex);
        $this->addReference('per-rep-list',$repIndex);

    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
