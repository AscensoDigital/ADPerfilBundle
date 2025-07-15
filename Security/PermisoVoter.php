<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 21-01-16
 * Time: 6:50
 */

namespace AscensoDigital\PerfilBundle\Security;


use AscensoDigital\PerfilBundle\Entity\Menu;
use AscensoDigital\PerfilBundle\Entity\Permiso;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PermisoVoter extends Voter
{
    const MENU = 'menu'; //slug de menu
    const PERMISO = 'permiso'; // nombre del permiso

    private $perfil_id;
    private $permisos;

    private $em;
    private $session;

    public function __construct(Session $session, EntityManager $em, $sessionName)
    {
        $this->session = $session;
        $this->em = $em;
        $this->permisos = array();
        $this->perfil_id = $session->get($sessionName, null);
    }


    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::MENU, self::PERMISO))) {
            return false;
        }

        if ($attribute == self::MENU && !is_null($subject) && !$subject instanceof Menu) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (is_null($subject)) {
            return true;
        }

        if (null === $this->perfil_id) {
            return false;
        }

        switch ($attribute) {
            case self::MENU:
                // carga de permisos de menu, sino estan cargados
                if (!isset($this->permisos[self::MENU])) {
                    try {
                        $menus = $this->em->getRepository('ADPerfilBundle:Menu')
                            ->findArrayPermisoByPerfil($this->perfil_id);
                        $this->permisos[self::MENU] = $menus[self::MENU] ?? [];
                    } catch (\Exception $e) {
                        return false; // o loguea si prefieres
                    }
                }
                /** @var Menu $subject */
                if (in_array($subject->getSlug(), $this->permisos[self::MENU][Permiso::LIBRE] ?? [])) {
                    return true;
                }
                return in_array($subject->getSlug(), $this->permisos[self::MENU][Permiso::RESTRICT] ?? []);
            case self::PERMISO:
                // carga de permisos sino estan cargados
                if (!isset($this->permisos[self::PERMISO])) {
                    try {
                        $this->permisos[self::PERMISO] = $this->em
                            ->getRepository('ADPerfilBundle:PerfilXPermiso')
                            ->findArrayIdByPerfil($this->perfil_id);
                    } catch (\Exception $e) {
                        return false; // o loguea si prefieres
                    }
                }
                return in_array($subject, $this->permisos[self::PERMISO] ?? []);
        }

        // ⚠️ Si llega aquí, el atributo es inválido: lanza la excepción como antes
        throw new \LogicException('El código no es reconocido!');
    }

}
