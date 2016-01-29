<?php
/**
 * namespace para nosso modulo Base\Entity
 */
namespace Base\Entity;

use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * class AbstractEntity
 * @package Base\Entity
 * Classe Responsável pela abstração das entidades
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 */
abstract class AbstractEntity
{
    /**
     * Método Construtor
     * Responsavel por Construir os Métodos Gets and Sets
     * @param array $options
     */
    public function __construct(Array $options = array())
    {
        $hydrator = new ClassMethods();
        $hydrator->hydrate($options, $this);
    }

    /**
     * Método toArray
     * Responsabel por montar todos os métodos gets
     * @return array
     */
    public function toArray()
    {
        $hydrator = new ClassMethods();
        return $hydrator->extract($this);
    }
}