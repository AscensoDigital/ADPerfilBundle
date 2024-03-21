<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 03-04-16
 * Time: 23:50
 */

namespace AscensoDigital\PerfilBundle\DataFixtures\ORM;


use AscensoDigital\PerfilBundle\Entity\Menu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadMenuData extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $mnMapa= new Menu();
        $mnMapa->setOrden(100)
            ->setDescripcion('Mapa del Sitio según perfil')
            ->setNombre('Mapa del Sitio')
            ->setRoute('ad_perfil_mapa_sitio')
            ->setColor($this->getReference('clr-verde'))
            ->setIcono('fa fa-sitemap')
            ->setPermiso($this->getReference('per-mapa-sitio-index'));
        $manager->persist($mnMapa);
        $this->addReference('ad-perfil-mn-mapa',$mnMapa);

        $mnConfig= new Menu();
        $mnConfig->setOrden(99)
            ->setDescripcion('Inicializar el sistema de menu y permisos')
            ->setNombre('Configuración')
            ->setColor($this->getReference('clr-negro'))
            ->setIcono('fa fa-cogs')
            ->setPermiso($this->getReference('per-config-index'));
        $manager->persist($mnConfig);
        $this->addReference('ad-perfil-mn-config',$mnConfig);

        $perNew= new Menu();
        $perNew->setMenuSuperior($mnConfig)
            ->setOrden(1)
            ->setDescripcion('Crear Permisos y asociar a los perfiles')
            ->setNombre('Crear Permiso')
            ->setRoute('ad_perfil_permiso_new')
            ->setColor($this->getReference('clr-verde'))
            ->setIcono('fa fa-unlock-alt')
            ->setPermiso($this->getReference('per-per-new'));
        $manager->persist($perNew);
        $this->addReference('ad-perfil-mn-per-new',$perNew);

        $perList= new Menu();
        $perList->setMenuSuperior($mnConfig)
            ->setOrden(2)
            ->setDescripcion('Listar los permisos asignados')
            ->setNombre('Listar Permisos')
            ->setRoute('ad_perfil_permiso_list')
            ->setColor($this->getReference('clr-rosado'))
            ->setIcono('fa fa-list-ul')
            ->setPermiso($this->getReference('per-per-list'));
        $manager->persist($perList);
        $this->addReference('ad-perfil-mn-per-list',$perList);

        $perEditPermiso= new Menu();
        $perEditPermiso->setMenuSuperior($perList)
            ->setOrden(1)
            ->setVisible(false)
            ->setDescripcion('Editar los roles que pueden utilizar el permiso')
            ->setNombre('Editar Perfiles por Permiso')
            ->setRoute('ad_perfil_permiso_edit')
            ->setColor($this->getReference('clr-celeste'))
            ->setIcono('fa fa-edit')
            ->setPermiso($this->getReference('per-per-edit'));
        $manager->persist($perEditPermiso);
        $this->addReference('ad-perfil-mn-per-edit-permiso',$perEditPermiso);

        $perEditPerfil= new Menu();
        $perEditPerfil->setMenuSuperior($perList)
            ->setOrden(2)
            ->setVisible(false)
            ->setDescripcion('Editar los permisos de un perfil')
            ->setNombre('Editar Permisos por Perfil')
            ->setRoute('ad_perfil_permiso_edit_perfil')
            ->setColor($this->getReference('clr-celeste'))
            ->setIcono('fa fa-edit')
            ->setPermiso($this->getReference('per-per-edit'));
        $manager->persist($perEditPerfil);
        $this->addReference('ad-perfil-mn-per-edit-perfil',$perEditPerfil);

        $mnNew= new Menu();
        $mnNew->setMenuSuperior($mnConfig)
            ->setOrden(3)
            ->setDescripcion('Permite agregar un menu invisible a los principales')
            ->setNombre('Crear Menu')
            ->setRoute('ad_perfil_menu_new')
            ->setColor($this->getReference('clr-celeste'))
            ->setIcono('fa fa-navicon')
            ->setPermiso($this->getReference('per-menu-new'));
        $manager->persist($mnNew);
        $this->addReference('ad-perfil-mn-menu-new',$mnNew);

        $mnRepo= new Menu();
        $mnRepo->setOrden(98)
            ->setDescripcion('Listado de los descargables del sistema')
            ->setNombre('Reportes')
            ->setColor($this->getReference('clr-gris'))
            ->setIcono('fa fa-file-excel-o')
            ->setPermiso($this->getReference('per-rep-list'));
        $manager->persist($mnRepo);
        $this->addReference('ad-perfil-mn-repo',$mnRepo);

        $repList= new Menu();
        $repList->setMenuSuperior($mnRepo)
            ->setOrden(1)
            ->setDescripcion('Listar los reportes disponibles')
            ->setNombre('Listar Reportes')
            ->setRoute('ad_perfil_reportes')
            ->setColor($this->getReference('clr-amarillo'))
            ->setIcono('fa fa-list-ul')
            ->setPermiso($this->getReference('per-rep-list'));
        $manager->persist($repList);
        $this->addReference('ad-perfil-mn-repo-list',$repList);

        $repNew= new Menu();
        $repNew->setMenuSuperior($mnRepo)
            ->setOrden(2)
            ->setDescripcion('Permite configurar un nuevo reporte de PerfilBundle')
            ->setNombre('Crear Reporte')
            ->setRoute('ad_perfil_reporte_new')
            ->setColor($this->getReference('clr-celeste'))
            ->setIcono('fa fa-plus')
            ->setPermiso($this->getReference('per-rep-new'));
        $manager->persist($repNew);
        $this->addReference('ad-perfil-mn-repo-new',$repNew);

        $repEdit= new Menu();
        $repEdit->setMenuSuperior($repList)
            ->setVisible(false)
            ->setOrden(1)
            ->setDescripcion('Permite editar la configuración de un reporte de PerfilBundle')
            ->setNombre('Editar Reporte')
            ->setRoute('ad_perfil_reporte_edit')
            ->setColor($this->getReference('clr-cafe'))
            ->setIcono('fa fa-pencil-square-o')
            ->setPermiso($this->getReference('per-rep-edit'));
        $manager->persist($repEdit);
        $this->addReference('ad-perfil-mn-repo-edit',$repEdit);

        $repLoad= new Menu();
        $repLoad->setMenuSuperior($repList)
            ->setVisible(false)
            ->setOrden(2)
            ->setDescripcion('Permite dejar estatico un reporte de PerfilBundle')
            ->setNombre('Cargar Reporte Estático')
            ->setRoute('ad_perfil_reporte_load_estatico')
            ->setColor($this->getReference('clr-rojo'))
            ->setIcono('fa fa-link')
            ->setPermiso($this->getReference('per-rep-load'));
        $manager->persist($repLoad);
        $this->addReference('ad-perfil-mn-repo-load',$repLoad);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(LoadColorData::class,
            LoadPermisoData::class);
    }
}