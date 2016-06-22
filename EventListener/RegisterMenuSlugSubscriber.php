<?php

namespace AscensoDigital\PerfilBundle\EventListener;


use AscensoDigital\PerfilBundle\Entity\Menu;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Description of RegisterModificadorSubscriber
 *
 * @author claudio
 */
class RegisterMenuSlugSubscriber implements EventSubscriber {
    private $slugify;

    public function __construct($slugify)
    {
        $this->slugify = $slugify;
    }

    public function getSubscribedEvents()
    {
        return array('prePersist', 'preUpdate');
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Menu){
            $entity->generateSlug($this->slugify);
        }
    }
}
