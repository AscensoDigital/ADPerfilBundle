<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 05-04-16
 * Time: 11:01
 */

namespace AscensoDigital\PerfilBundle\DataFixtures\ORM;


use AscensoDigital\PerfilBundle\Entity\ReporteCriterio;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadReporteCriterioData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $periodo= $manager->getRepository('ADPerfilBundle:ReporteCriterio')->findOneBy(['nombre' => 'periodo']);
        if(!$periodo) {
            $periodo = new ReporteCriterio();
            $periodo->setNombre('periodo')
                ->setTitulo('Período');
            $manager->persist($periodo);
        }
        $this->setReference('rpc-periodo', $periodo);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }
}