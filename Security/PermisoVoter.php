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

    public function __construct(Session $session, EntityManager $em, $sessionName)
    {
        $this->perfil_id = $session->get($sessionName,null);
        try{
            $menus = $em->getRepository('ADPerfilBundle:Menu')->findArrayPermisoByPerfil($this->perfil_id);
            $this->permisos[self::MENU] = isset($menus[self::MENU]) ? $menus[self::MENU] : array();

            if(!is_null($this->perfil_id)) {
                $this->permisos[self::PERMISO] = $em->getRepository('ADPerfilBundle:PerfilXPermiso')->findArrayIdByPerfil($this->perfil_id);
            }
        } catch(DBALException $e) {
            return;
        } catch(\PDOException $e) {
            return;
        }
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
        if(is_null($subject)){
            return true;
        }

        switch($attribute){
            case self::MENU:
                /** @var Menu $subject */
                if(isset($this->permisos[$attribute][Permiso::LIBRE]) && in_array($subject->getSlug(), $this->permisos[$attribute][Permiso::LIBRE])){
                    return true;
                }
                if(!isset($this->permisos[$attribute][Permiso::RESTRICT])){
                    return false;
                }
                return in_array($subject->getSlug(), $this->permisos[$attribute][Permiso::RESTRICT]);
            case self::PERMISO:
                if(!isset($this->permisos[$attribute])){
                    return false;
                }
                return in_array($subject, $this->permisos[$attribute]);
        }

        $user = $token->getUser();
        if (!is_object($user)) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if(is_null($this->perfil_id)){
            return false;
        }

        throw new \LogicException('El código no es reconocido!');
    }
}
