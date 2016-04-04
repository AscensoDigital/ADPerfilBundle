<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 03-04-16
 * Time: 23:50
 */

namespace AscensoDigital\PerfilBundle\DataFixtures\ORM;


use AscensoDigital\PerfilBundle\Entity\Menu;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMenuData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $mnConfig= new Menu();
        $mnConfig->setOrden(99)
            ->setNombre('Configuración')
            ->setDescripcion('Inicializar el sistema de menu y permisos')
            ->setColor($this->getReference('clr-negro'))
            ->setIcono('fa fa-cogs')
            ->setPermiso($this->getReference('per-config-index'));
        $manager->persist($mnConfig);

        $perNew= new Menu();
        $perNew->setMenuSuperior($mnConfig)
            ->setOrden(1)
            ->setNombre('Crear Permiso')
            ->setDescripcion('Crear Permisos y asociar a los perfiles')
            ->setColor($this->getReference('clr-verde'))
            ->setIcono('fa fa-unlock-alt')
            ->setPermiso($this->getReference('per-per-new'));
        $manager->persist($perNew);

        $perList= new Menu();
        $perList->setMenuSuperior($mnConfig)
            ->setOrden(2)
            ->setNombre('Listar Permisos')
            ->setDescripcion('Listar los permisos asignados')
            ->setColor($this->getReference('clr-rosado'))
            ->setIcono('fa fa-list-ul')
            ->setPermiso($this->getReference('per-per-list'));
        $manager->persist($perList);

        $mnNew= new Menu();
        $mnNew->setMenuSuperior($mnConfig)
            ->setOrden(3)
            ->setNombre('Crear Menu')
            ->setDescripcion('Permite agregar un menu invisible a los principales')
            ->setColor($this->getReference('clr-celeste'))
            ->setIcono('fa fa-navicon')
            ->setPermiso($this->getReference('per-menu-new'));
        $manager->persist($mnNew);

        $mnRepo= new Menu();
        $mnRepo->setOrden(98)
            ->setNombre('Reportes')
            ->setDescripcion('Listado de los descargables del sistema')
            ->setColor($this->getReference('clr-gris'))
            ->setIcono('fa fa-file-excel-o')
            ->setPermiso($this->getReference('per-rep-list'));
        $manager->persist($mnRepo);

        $repList= new Menu();
        $repList->setMenuSuperior($mnRepo)
            ->setOrden(1)
            ->setNombre('Listar Reportes')
            ->setDescripcion('Listar los reportes disponibles')
            ->setColor($this->getReference('clr-amarillo'))
            ->setIcono('fa fa-list-ul')
            ->setPermiso($this->getReference('per-rep-list'));
        $manager->persist($repList);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}