<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 28-01-16
 * Time: 3:17
 */

namespace AscensoDigital\PerfilBundle\Repository;


use AscensoDigital\PerfilBundle\Entity\Color;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ColorRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Color::class);
    }

    public function getQueryBuilderOrderNombre() {
        return $this->createQueryBuilder('adp_c')
            ->orderBy('adp_c.nombre');
    }

}
