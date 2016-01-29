<?php
/**
 * namespace para nosso modulo Base\Form
 */
namespace GeraMvc\Form;

/**
 * class GeraFilter
 * Reponsavel por Gerar Novos Filtros
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package GeraMvc\Controller
 */
class GeraFilter {

    private $dados;
    private $moduloUp;
    private $nomeTabelaUp;

    public function __construct($nomeModulo,$nomeTabela,$dados)
    {
        $this->moduloUp = ucfirst($nomeModulo); // Nome do Modulo aonde o Formulário sera gerado, com o primeira letra em Maiuscula
        $this->nomeTabelaUp = ucfirst($nomeTabela); // Nome da Tabela Com a primeira Leita em Maiuscula
        $this->dados = $dados; // Dados para a Popular o Formulário
    }

    public function GeraFilter(){
        // Verifica se a pasta Filter existe, se nao ele cria ela
        if(!file_exists('./module/'.$this->moduloUp.'/src/'.$this->moduloUp.'/Form/Filter')){
            mkdir('./module/'.$this->moduloUp.'/src/'.$this->moduloUp.'/Form/Filter', 0777, true);
        }

        // Nome do Filter
        $nomeFilter = "./module/".$this->moduloUp."/src/".$this->moduloUp."/Form/Filter/".$this->nomeTabelaUp."Filter.php";
        //var_dump($nomeFilter);die();

        // Data Criação
        $dataCriacao = new \DateTime('now');
        $dataCriacao = $dataCriacao->format('d/m/Y');

        // Monta o Filter
        $conteudo = <<<EOD
<?php
/**
 * namespace para nosso modulo $this->moduloUp\Form\Filter
 */
namespace $this->moduloUp\Form\Filter;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

/**
 * class $this->nomeTabelaUp|Filter
 * Filtro da classe $this->nomeTabelaUp|Form
 * Responsavel por filtrar todos os campos do forumularios
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package Admin\Form\Filter
 * Data Criação $dataCriacao
 */
class $this->nomeTabelaUp|Filter extends InputFilter
{
    public function __construct()
    {

EOD;
        foreach($this->dados as $dados):
            // Retira os campos desnecessarios para o formulários
            if(($dados['nomeCampo'] != 'id') && ($dados['nomeCampo'] != 'salt') && ($dados['nomeCampo'] != 'activation_key')
                && ($dados['nomeCampo'] != 'data_cadastro') && ($dados['nomeCampo'] != 'data_alteracao') && ($dados['nomeCampo'] != 'status')
                && ($dados['nomeCampo'] != 'senha')):
                $nomeCampo = $dados['nomeCampo'];
                $quantCaractere = $dados['quantCaractere'];
                $conteudo .= <<<EOD

        //Input $nomeCampo
            €$nomeCampo = new Input('$nomeCampo');
            €$nomeCampo|->setRequired(true)
                ->getFilterChain()
                ->attach(new StringTrim())
                ->attach(new StripTags());
            €$nomeCampo|->getValidatorChain()->attach(new NotEmpty());
            €this->add(€$nomeCampo);

EOD;
            endif;

        endforeach;
        // Monta o Filter
        $conteudo .= <<<EOD
        }
}
EOD;


        // Retira o |
        $conteudo = str_replace("|","",$conteudo);
        $conteudo = str_replace("€","$",$conteudo);

        // Se existir o controller e fazer uma copia do controller antigo
        if(!file_exists($nomeFilter)){
            file_put_contents($nomeFilter, $conteudo);
        }
        else {
            copy($nomeFilter,
                $nomeFilter.".old".date("d_m_Y_H:s"));
            file_put_contents($nomeFilter, $conteudo);
        }

//var_dump($nomeController);die();
        return "Filtro Criado com Sucesso<br>";

    }

}