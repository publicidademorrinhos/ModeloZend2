<?php
/**
 * namespace para nosso modulo Base
 */
namespace Base\View;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;


/**
 * Class UserIdentity
 * Respnsavel por verificar se o usuario esta logado ou nao
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 */

class UserIdentity extends AbstractHelper {

    protected $authService;

    public function getAuthService() {

        return $this->authService;
    }

    public function __invoke($namespace = null) {
        $sessionStorage = new SessionStorage($namespace);
        $this->authService = new AuthenticationService;
        $this->authService->setStorage($sessionStorage);

        if ($this->getAuthService()->hasIdentity()) {
            return $this->getAuthService()->getIdentity();
        }
        else
            return false;
    }

}
