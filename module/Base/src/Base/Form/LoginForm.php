<?php
/**
 * namespace para nosso modulo Admin
 */

namespace Base\Form;
use Zend\Form\Element\Button;
use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Form;


/**
 * Class LoginForm
 * Respnsavel por efetuar o login e a senha no sistema
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 */
class LoginForm extends Form
{
    public function __construct()
    {
        parent::__construct(null);
        $this->setAttributes(array(
            'method' => 'POST',
            'role' => 'form'
        ));

        //Input login
        $login = new Text('login');
        $login->setLabel('Entre com o Login.: ')
            ->setAttributes(array(
                'maxlength' => 40,
                'class' => 'form-control',
                'id' => 'login',
                'placeholder' => 'Entre com o Login.:',
            ));
        $this->add($login);

        //Input senha
        $senha = new Password('senha');
        $senha->setLabel('Entre com a Senha.: ')
            ->setAttributes(array(
                'class' => 'form-control',
                'id' => 'senha',
                'placeholder' => 'Entre com o Senha.:'
            ));
        $this->add($senha);

        //Botao submit
        $button = new Button('submit');
        $button->setLabel('Logar')
            ->setAttributes(array(
                'type' => 'submit',
                'class' => 'btn btn-success'
            ));
        $this->add($button);
    }

} 