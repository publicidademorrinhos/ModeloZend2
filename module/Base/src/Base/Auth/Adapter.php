<?php
/**
 * namespace para nosso modulo Base\Auth
 */

namespace Base\Auth;

use Zend\Authentication\Adapter\AdapterInterface,
    Zend\Authentication\Result;

use Doctrine\ORM\EntityManager;

/**
 * Class Adapter
 * Classe Responsável pela autenticação do usuario no sistema
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package Base\Auth
 */
class Adapter  implements AdapterInterface
{
    // Atributos
    protected $em;
    protected $login;
    protected $senha;
    protected $entity;
    protected $metodoLogin; // Método da Classe que sera responsavel por verificar o login

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param $login
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param $senha
     * @return $this
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetodoLogin()
    {
        return $this->metodoLogin;
    }

    /**
     * @param mixed $metodoLogin
     */
    public function setMetodoLogin($metodoLogin)
    {
        $this->metodoLogin = $metodoLogin;
        return $this;
    }




    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        // Método que Faz a verificação dos dados para login
        $metodo = $this->getMetodoLogin();
        $login = $this->em->getRepository($this->getEntity())
            ->$metodo(new $this->entity ,$this->getLogin(),$this->getSenha());
        if($login):
            return new Result(Result::SUCCESS,array('login'=>$login),array('Ok'));
            //var_dump($login);die("Adapter L 121");
        else:
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, array("Não foi possivel conectar ao banco \n login ou seja não conferem"));
            //die("AdapterError L 121");
        endif;
    }
}