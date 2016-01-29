<?php
/**
 * namespace para nosso modulo Base\Auth
 */

namespace Base\Auth;

use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session;

/**
 * Class AuthenticationFactory
 * Classe Responsável pela autenticação do usuario no sistema
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package Base\Auth
 */

class AuthenticationFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var $entityManager \Doctrine\ORM\EntityManager
         */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        return new AuthenticationService(new Session(),new Adapter($entityManager));
    }
}