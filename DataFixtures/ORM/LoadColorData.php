<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 03-04-16
 * Time: 20:44
 */

namespace AscensoDigital\PerfilBundle\DataFixtures\ORM;


use AscensoDigital\PerfilBundle\Entity\Color;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadColorData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $amarillo=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Amarillo']);
        if(!$amarillo) {
            $amarillo = new Color();
            $amarillo->setNombre('Amarillo')->setCodigo('fff647');
            $manager->persist($amarillo);
        }
        $this->addReference('clr-amarillo',$amarillo);

        $azul=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Azul']);
        if(!$azul) {
            $azul = new Color();
            $azul->setNombre('Azul')->setCodigo('0000ff');
            $manager->persist($azul);
        }
        $this->addReference('clr-azul',$azul);

        $blanco=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Blanco']);
        if(!$blanco) {
            $blanco = new Color();
            $blanco->setNombre('Blanco')->setCodigo('ffffff');
            $manager->persist($blanco);
        }
        $this->addReference('clr-blanco',$blanco);

        $cafe=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Cafe']);
        if(!$cafe) {
            $cafe = new Color();
            $cafe->setNombre('Cafe')->setCodigo('9d7050');
            $manager->persist($cafe);
        }
        $this->addReference('clr-cafe',$cafe);

        $celeste=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Celeste']);
        if(!$celeste) {
            $celeste = new Color();
            $celeste->setNombre('Celeste')->setCodigo('3875d7');
            $manager->persist($celeste);
        }
        $this->addReference('clr-celeste',$celeste);

        $cian=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Cian']);
        if(!$cian) {
            $cian = new Color();
            $cian->setNombre('Cian')->setCodigo('5ae0d9');
            $manager->persist($cian);
        }
        $this->addReference('clr-cian',$cian);

        $gris=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Gris']);
        if(!$gris) {
            $gris = new Color();
            $gris->setNombre('Gris')->setCodigo('8c8c78');
            $manager->persist($gris);
        }
        $this->addReference('clr-gris',$gris);

        $morado=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Morado']);
        if(!$morado) {
            $morado = new Color();
            $morado->setNombre('Morado')->setCodigo('7a0c5d');
            $manager->persist($morado);
        }
        $this->addReference('clr-morado',$morado);

        $naranjo=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Naranjo']);
        if(!$naranjo) {
            $naranjo = new Color();
            $naranjo->setNombre('Naranjo')->setCodigo('ff8922');
            $manager->persist($naranjo);
        }
        $this->addReference('clr-naranjo',$naranjo);

        $negro=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Negro']);
        if(!$negro) {
            $negro = new Color();
            $negro->setNombre('Negro')->setCodigo('000000');
            $manager->persist($negro);
        }
        $this->addReference('clr-negro',$negro);

        $rojo=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Rojo']);
        if(!$rojo) {
            $rojo = new Color();
            $rojo->setNombre('Rojo')->setCodigo('e8121d');
            $manager->persist($rojo);
        }
        $this->addReference('clr-rojo',$rojo);

        $rosado=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Rosado']);
        if(!$rosado) {
            $rosado = new Color();
            $rosado->setNombre('Rosado')->setCodigo('ff6be1');
            $manager->persist($rosado);
        }
        $this->addReference('clr-rosado',$rosado);

        $verde=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Verde']);
        if(!$verde) {
            $verde = new Color();
            $verde->setNombre('Verde')->setCodigo('76ff61');
            $manager->persist($verde);
        }
        $this->addReference('clr-verde',$verde);

        $violeta=$manager->getRepository('ADPerfilBundle:Color')->findOneBy(['nombre' => 'Violeta']);
        if(!$violeta) {
            $violeta = new Color();
            $violeta->setNombre('Violeta')->setCodigo('bc12e6');
            $manager->persist($violeta);
        }
        $this->addReference('clr-violeta',$violeta);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
