<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 20-01-16
 * Time: 19:17
 */

namespace AscensoDigital\PerfilBundle\Model;


use Doctrine\ORM\EntityManager;

class PerfilManager
{
    protected $class;
    protected $repository;

    /**
     * Constructor.
     *
     * @param EntityManager  $em
     * @param string         $class
     */
    public function __construct(EntityManager $em, $class) {

        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    public function find($id) {
        return $this->repository->find($id);
    }

    public function findPerfilBy(array $criteria) {
        return $this->repository->findOneBy($criteria);
    }

    public function findPerfils() {
        return $this->repository->findAll();
    }

    public function getClass() {
        return $this->class;
    }
}
