<?php
/**
 * namespace para nosso modulo Base\Service
 */
namespace Base\Service;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class AbstractService
 * @package Base\Service
 * Classe Responsável pela manipulação de dados, como inserir alterar e excluir registros.
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1

 */
abstract class AbstractService
{
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var
     */
    protected $entity; // Nome da Entidade


    /**
     * Método Construtor
     * Responsavel por passar o EntityManager do Doctrine
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Método Save
     * Responsavel por realizar as incluesões e auteração dos dados nas Entidades
     * Recebe os dados atravez de Array()
     * @param array $data
     * Retorna um object
     * @return object
     */
    public function save(Array $data = array())
    {
        /**
         * Verifica se foi passado o Id, caso tenha passado.
         * Sera auterado os dados, caso não tenho
         * Sera incluido os dados
         */
        if (isset($data['id'])){

            // Recebe os dados da Entidade atravez do Id
            $entity = $this->em->getReference($this->entity, $data['id']);

            // Instancia a Class ClassMethods, para poder trabalhar com os sets
            $hydrator = new ClassMethods();
            $hydrator->hydrate($data, $entity);

        }else{
            // Cria uma novas Entidade com os dados para inclusão
            $entity = new $this->entity($data);
        }

        // Persist os dado na Entidade
        $this->em->persist($entity);
        // Grava os Dados na Entidade
        $this->em->flush();

        // Retorna a Entidade
        return $entity;
    }

    /**
     * Método Remove
     * Responsavel por Excluir um registro da Entidade
     * Receber Id e Retorna um Array(), com os dados a ser excluido
     * @param array $data
     * @return null|object
     */
    public function remove(Array $data = array())
    {
        // Pesquisa na Entidade
        $entity = $this->em->getRepository($this->entity)->findOneBy($data);

        // Verifica se exister o id na Entidade, caso exista retorna os dados
        if ($entity){
            $data['status'] = 0;
            // Revome
            $this->save($data);

            // Retorna a Entidade
            return $entity;
        }
    }
}