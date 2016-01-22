<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 19-01-16
 * Time: 19:51
 */

namespace AscensoDigital\PerfilBundle\Model;

use FOS\UserBundle\Model\User as BaseUser;

abstract class User extends BaseUser
{
    /**
     * @var array
     */
    protected $perfils;


    public function __construct() {
        parent::__construct();

        $this->perfils=array();
    }

    abstract public function getPerfils();
}