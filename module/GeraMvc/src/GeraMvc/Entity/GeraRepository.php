<?php
/**
 * namespace para nosso modulo GeraMvc\Entity
 */
namespace GeraMvc\Entity;

/**
 * class GeraRepository
 * Reponsavel por Gerar Novos Repository, com algumas configurações de pesquisas ja configuradas.
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package GeraMvc\Entity
 */

class GeraRepository {

    // Atraibutos
    private $nomeTabelaUp;
    private $campoPesquisa;
    private $campoPesquisaUp;

    /**
     * Método __construct($nomeTabela,$campoPesquisa)
     * Responsavel por criar um repostitory com os Métodos.
     * Método Responsavel por fazer uma busca para o autocomplete da pagina Index.phtml
     * @param $nomeTabelaUp
     * @param $campoPesquisa
     */
    public function __construct($nomeTabela,$campoPesquisa)
    {
        $this->nomeTabelaUp = ucfirst($nomeTabela); // Nome do Repository
        $this->campoPesquisa = $campoPesquisa; // Nome do Campo para efeturar as pesquisas
        $this->campoPesquisaUp = ucfirst($campoPesquisa); // Nome do Campo em Maiuscula para efeturar as pesquisas
    }

    public function geraRepository()
    {
        // Verifica se a pasta viw da tabela existe, se nao ele cria ela
        if(!file_exists('./module/Admin/src/Admin/Entity/Repository/')){
            mkdir('./module/Admin/src/Admin/Entity/Repository/', 0777, true);
        }

        // Nome do controller a ser criado
        $nomeRepository = "./module/Admin/src/Admin/Entity/Repository/".$this->nomeTabelaUp."Repository.php";

        // Data de Criação
        $dataCriacao = new \DateTime('now');
        $dataCriacao = $dataCriacao->format('d/m/Y');
        // Monta o Conteudo do Controller
        $conteudo = <<<EOD
<?php
/**
 * namespace para nosso modulo Admin\Entity\Repository
 */
namespace Admin\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * $this->nomeTabelaUp|Repository
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package $this->moduloUp\Controller
 * Data de Criação $dataCriacao
 *
 */
class $this->nomeTabelaUp|Repository extends EntityRepository
{
    public function Busca$this->campoPesquisaUp(€$this->campoPesquisa)
    {
        /**
         * @var €busca \Admin\Entity\|$this->nomeTabelaUp
         */
        €busca = €this->createQueryBuilder('a')
            ->select('a.$this->campoPesquisa')
            ->where('a.$this->campoPesquisa like :n1')
            ->setParameter('n1','%'.€$this->campoPesquisa.'%')
            ->getQuery()
            ->getArrayResult()
        ;
        return €busca;

    }

    public function UltimoRegistro()
    {
        €registro = €this->createQueryBuilder('r')
            ->select('max(r.id)')
            ->getQuery()
            ->getOneOrNullResult();
        //Debug::dump(€registro);die();
        return €registro;
    }
}
EOD;


        // Retira o |
        $conteudo = str_replace("|","",$conteudo);
        $conteudo = str_replace("€","$",$conteudo);

        // Se existir o controller e fazer uma copia do controller antigo
        if(!file_exists($nomeRepository)){
            file_put_contents($nomeRepository, $conteudo);
        }
        else {
            copy($nomeRepository,
                $nomeRepository.".old".date("d_m_Y_H:s"));
            file_put_contents($nomeRepository, $conteudo);
        }

//var_dump($nomeRepository);die();
        return "Repository Criado com Sucesso<br>";

    }


}