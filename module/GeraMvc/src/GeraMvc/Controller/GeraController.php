<?php
/**
* namespace para nosso modulo GeraMvc\Controller
*/
namespace GeraMvc\Controller;

/**
 * class GeraController
 * Reponsavel por Gerar Novos Controller
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package GeraMvc\Controller
 */
class GeraController {
    // Atributos
    protected $tabelaUp;
    protected $moduloUp;
    protected $campoPesquisaUp;
    protected $modulo;
    protected $tabela;
    protected $campoPesquisa;

    /**
     * Método __construct($nomeModulo,$nomeTabela,$campoPesquisa)
     * Responsavel por passar os nomes do Modulo, das Tabelas e o Campo de Pesquisa
     * @param $nomeModulo
     * @param $nomeTabela
     * @param $campoPesquisa
     */
    public function __construct($nomeModulo,$nomeTabela,$campoPesquisa)
    {
        $this->tabelaUp = ucfirst($nomeTabela); // Nome da Tabela, a ser gerado o controller, com o primeira letra em Maiuscula
        $this->moduloUp = ucfirst($nomeModulo); // Nome do Modulo aonde o controller sera gerado, com o primeira letra em Maiuscula
        $this->campoPesquisaUp = ucfirst($campoPesquisa); // Nome do Campo para Gera a Pesquisa
        $this->tabela = $nomeTabela; // Nome da Tabela, a ser gerado o controller, em minusculo
        $this->modulo = $nomeModulo; // Nome da Modulo, a ser gerado o controller, em minusculo
        $this->campoPesquisa = $campoPesquisa; // Nome do Campo para Gera a Pesquisa
    }

    /**
     * Método geraController
     * Responsavel por Gera o Controller, ele verifica se a existe o controller, se exister ele clona o antigo, muda o nome
     * e criar um novo Controller
     * @return string
     */
    public function geraController(){
        // Verifica se a pasta controller existe, se nao ele cria ela
        if(!file_exists('./module/'.$this->moduloUp.'/src/'.$this->moduloUp.'/Controller/')){
            mkdir('./module/'.$this->moduloUp.'/src/'.$this->moduloUp.'/Controller/', 0777, true);
        }

        // Nome do controller a ser criado
        $nomeController = "./module/".$this->moduloUp."/src/".$this->moduloUp."/Controller/".$this->tabelaUp."Controller.php";

        // Data de Criação
        $dataCriacao = new \DateTime('now');
        $dataCriacao = $dataCriacao->format('d/m/Y');
        // Monta o Conteudo do Controller
        $conteudo = <<<EOD
<?php
/**
 * namespace para nosso modulo $this->moduloUp\Controller
 */

namespace $this->moduloUp\Controller;

use Base\Controller\AbstractController;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
/**
 * class $this->tabelaUp\Controller
 * Controller Responsavel por manipular os dados da Entidade $this->tabelaUp
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package $this->moduloUp\Controller
 * Data de Criação $dataCriacao
 */
class $this->tabelaUp|Controller extends AbstractController
{
    // Método Contrutor
    function __construct()
    {
        €this->form = '$this->moduloUp\Form\|$this->tabelaUp|Form';
        €this->controller = '$this->tabelaUp';
        €this->route = '$this->modulo-$this->tabela/default';
        €this->service = '$this->moduloUp\Service\|$this->tabelaUp|Service';
        €this->entity = 'Admin\Entity\|$this->tabelaUp';
        €this->itemPorPaigina = 20;
        €this->campoOrder = '$this->campoPesquisa';
        €this->order = 'ASC';
        €this->campoPesquisa = 'status';
        €this->dadoPesquisa = '1';
    }

    public function detalhesAction()
    {
        // filtra id passsado pela url
        €id = (int) €this->params()->fromRoute('id', 0);
        // se id = 0 ou não informado redirecione
        €lista = €this->getEm()->getRepository(€this->entity)->findBy(array('id' => €id));

        // dados eviados para detalhes.phtml
        return (new ViewModel())
            ->setTerminal(€this->getRequest()->isXmlHttpRequest())
            ->setVariable('dados', €lista)
            ;
    }

    public function pesquisasAction()
    {
        €nome = €this->params()->fromQuery('query', null);
        if (isset(€nome)) {
            // Resebe os dados da Entidade passada
            €list = €this->getEm()->getRepository(€this->entity)->Busca$this->campoPesquisaUp(€$this->campoPesquisa);

        }
        return new JsonModel(€list);
    }

    public function pesquisaAction()
    {
        // Recebe os dados vendo pela Request(POST,GET)
        €request = €this->getRequest()->getPost()->toArray();
        €nomePesquisa = €request['search'];
        €list = €this->getEm()->getRepository(€this->entity)->findBy(array('$this->campoPesquisa' => €nomePesquisa));
        return new ViewModel(array('data' => €list));

    }

    /*
    public function inserirAction()
    {
        parent::inserirAction();
    }
    */

    /*
    public function editarAction()
    {
        parent::editarAction();
    }
    */

    /*
    public function excluirAction()
    {
        parent::excluirAction();
    }
    */
}

EOD;

        // Retira o |
        $conteudo = str_replace("|","",$conteudo);
        $conteudo = str_replace("€","$",$conteudo);

        // Se existir o controller e fazer uma copia do controller antigo
        if(!file_exists($nomeController)){
            file_put_contents($nomeController, $conteudo);
        }
        else {
            copy($nomeController,
                $nomeController.".old".date("d_m_Y_H:s"));
            file_put_contents($nomeController, $conteudo);
        }

//var_dump($nomeController);die();
        return "Controller Criado com Sucesso<br>";
    }

}