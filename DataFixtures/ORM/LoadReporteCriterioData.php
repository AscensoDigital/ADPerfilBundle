<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 05-04-16
 * Time: 11:01
 */

namespace AscensoDigital\PerfilBundle\DataFixtures\ORM;


use AscensoDigital\PerfilBundle\Entity\ReporteCriterio;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager; // ✅ correcto para Symfony 2.x


class LoadReporteCriterioData extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $periodo= $manager->getRepository('ADPerfilBundle:ReporteCriterio')->findOneBy(array('nombre' => 'periodo'));
        if(!$periodo) {
            $periodo = new ReporteCriterio();
            $periodo->setNombre('periodo')
                ->setTitulo('Período');
            $manager->persist($periodo);
        }
        $this->setReference('rpc-periodo', $periodo);
        $manager->flush();
    }
}