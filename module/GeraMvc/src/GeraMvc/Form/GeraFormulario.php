<?php
/**
 * namespace para nosso modulo Base\Form
 */
namespace GeraMvc\Form;

/**
 * class GeraFormulário
 * Reponsavel por Gerar Novos Formulários
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package GeraMvc\Controller
 */
class GeraFormulario {

    private $dados;
    private $moduloUp;
    private $nomeTabelaUp;

    public function __construct($nomeModulo,$nomeTabela,$dados)
    {
        $this->moduloUp = ucfirst($nomeModulo); // Nome do Modulo aonde o Formulário sera gerado, com o primeira letra em Maiuscula
        $this->nomeTabelaUp = ucfirst($nomeTabela); // Nome da Tabela Com a primeira Leita em Maiuscula
        $this->dados = $dados; // Dados para a Popular o Formulário
    }

    public function GeraFormulario(){
        // Verifica se a pasta Formulario existe, se nao ele cria ela
        if(!file_exists('./module/'.$this->moduloUp.'/src/'.$this->moduloUp.'/Form/')){
            mkdir('./module/'.$this->moduloUp.'/src/'.$this->moduloUp.'/Form/', 0777, true);
        }
        // Nome do Formulario
        $nomeFormulario = "./module/".$this->moduloUp."/src/".$this->moduloUp."/Form/".$this->nomeTabelaUp."Form.php";
        //var_dump($nomeFormulario);die();

        // Data Criação
        $dataCriacao = new \DateTime('now');
        $dataCriacao = $dataCriacao->format('d/m/Y');
        // Monta o Formulario
        $conteudo = <<<EOD
<?php
/**
 * namespace para nosso modulo $this->moduloUp\Form
 */
namespace Admin\Form;

use Admin\Form\Filter\|$this->nomeTabelaUp|Filter;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * class $this->nomeTabelaUp\Form
 * Controller Responsavel por manipular o Formulario $this->nomeTabelaUp
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package $this->moduloUp\Controller
 * Data Criação $dataCriacao
 */
 class $this->nomeTabelaUp|Form extends Form
{
    public function __construct()
    {
        parent::__construct('$this->nomeTabelaUp|Form');
        €this->setAttributes(array(
            'method' => 'POST',
            'role' => 'form'
        ));

        €this->setInputFilter(new $this->nomeTabelaUp|Filter());
EOD;
        foreach($this->dados as $dados):
            $campos = explode('_', $dados['nomeCampo']);
            $campoToUp = ucfirst($campos[0]) . " " . ucfirst($campos[1]);

            // Retira os campos desnecessarios para o formulários
            if(($dados['nomeCampo'] != 'id') && ($dados['nomeCampo'] != 'salt') && ($dados['nomeCampo'] != 'activation_key')
                && ($dados['nomeCampo'] != 'data_cadastro') && ($dados['nomeCampo'] != 'data_alteracao') && ($dados['nomeCampo'] != 'status')
                && ($dados['nomeCampo'] != 'senha')):
                $nomeCampo = $dados['nomeCampo'];
                $quantCaractere = $dados['quantCaractere'];
                if($quantCaractere  ==""):
                    $quantCaractere = 10;
                endif;

                switch($campos[0]){
                    case 'cpf'  : $mascara = "'data-mask' => '999.999.999-99'"; break;
                    case 'cnpj' : $mascara = "'data-mask' => '99.999.999/9999-99'"; break;
                    case 'telefone' : $mascara = "'data-mask' => '(99)99999-9999'"; break;
                    case 'celular' : $mascara = "'data-mask' => '(99)99999-9999'"; break;
                    case 'data' :
                        $type = "'type' =>'date',";
                        break;
                    default:
                        $mascara = "";
                        $type = "";
                }
                $conteudo .= <<<EOD

        //Input $nomeCampo
        €$nomeCampo| = new Text('$nomeCampo');
        €$nomeCampo|->setLabel('$campoToUp.: ')
            ->setAttributes(array(
                'maxlength' => $quantCaractere,
                'class' => 'form-control',
                'id' => '$nomeCampo',
                'placeholder' => 'Entre com $campoToUp  .:',
                $mascara
                $type
            ));
        €this->add(€$nomeCampo);

EOD;
            endif;
            if($dados['nomeCampo'] == 'senha'):
                $nomeCampo = $dados['nomeCampo'];
                $conteudo .= <<<EOD

        //Input $nomeCampo
        €$nomeCampo| = new Password('$nomeCampo');
        €$nomeCampo|->setLabel('$nomeCampo.: ')
            ->setAttributes(array(
                'maxlength' => $quantCaractere,
                'class' => 'form-control',
                'id' => '$nomeCampo',
                'placeholder' => 'Entre com $nomeCampo  .:',
            ));
        €this->add(€$nomeCampo);

EOD;
            endif;
            if(($dados['nomeCampo'] == 'status')):
                $conteudo .= <<<EOD

                // Select status
        €status = new Select('status');
        €status->setLabel('Condição.:')
            ->setAttributes(array(
                'class' => 'form-control',
                'id' => 'status',
            ));
        €status->setValueOptions(array(
            '1' => 'ATIVO',
            '0' => 'DESTATIVADO',
        ));
        €this->add(€status);



EOD;

            endif;

        endforeach;
        // Monta o Formulario
        $conteudo .= <<<EOD
    }
}

EOD;
        // Retira o |
        $conteudo = str_replace("|","",$conteudo);
        $conteudo = str_replace("€","$",$conteudo);

        // Se existir o controller e fazer uma copia do controller antigo
        if(!file_exists($nomeFormulario)){
            file_put_contents($nomeFormulario, $conteudo);
        }
        else {
            copy($nomeFormulario,
                $nomeFormulario.".old".date("d_m_Y_H:s"));
            file_put_contents($nomeFormulario, $conteudo);
        }

//var_dump($nomeController);die();
        return "Formulário Criado com Sucesso<br>";
    }

}