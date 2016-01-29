<?php
/**
 * namespace para nosso modulo GeraMvc\View
 */
namespace GeraMvc\View;

/**
 * class GeraEditar
 * Reponsavel por Gerar Novos Arquivos Editar
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package GeraMvc\Controller
 */


class GeraEditar {
    // Atributos
    protected $modulo;
    protected $moduloUp;
    protected $tabela;
    protected $tabelaUp;
    protected $campoPesquisa;
    protected $dados;
    protected $campoPesquisaUp;

    /**
     * Método __construct($nomeModulo,$nomeTabela,$campoPesquisa)
     * Responsavel por passar os nomes do Modulo, das Tabelas e o Campo de Pesquisa
     * @param $nomeModulo
     * @param $nomeTabela
     * @param $campoPesquisa
     */
    public function __construct($nomeModulo,$nomeTabela,$campoPesquisa,$dados)
    {
        $this->tabelaUp = ucfirst($nomeTabela); // Nome da Tabela, a ser gerado o controller, com o primeira letra em Maiuscula
        $this->moduloUp = ucfirst($nomeModulo); // Nome do Modulo aonde o controller sera gerado, com o primeira letra em Maiuscula
        $this->campoPesquisaUp = ucfirst($campoPesquisa); // Nome do Campo para Gera a Pesquisa
        $this->tabela = $nomeTabela; // Nome da Tabela, a ser gerado o controller, em minusculo
        $this->modulo = $nomeModulo; // Nome da Modulo, a ser gerado o controller, em minusculo
        $this->campoPesquisa = $campoPesquisa; // Nome do Campo para Gera a Pesquisa
        $this->dados = $dados; // Dados para Montar a Listagem dos Dados
    }

    /**
     * Método geraIndex()
     * Responsavel por Gera o Inserir, ele verifica se a existe o index, se exister ele clona o antigo, muda o nome
     * e criar um novo Controller
     * @return string
     */
    public function geraEditar()
    {
        // Verifica se a pasta viw da tabela existe, se nao ele cria ela
        if(!file_exists('./module/'.$this->moduloUp.'/view/'.$this->modulo.'/'.$this->tabela.'/')){
            mkdir('./module/'.$this->moduloUp.'/view/'.$this->modulo.'/'.$this->tabela.'/', 0777, true);
        }

        // Nome do index a ser criado
        $nomeEditar = "./module/".$this->moduloUp."/view/".$this->modulo."/".$this->tabela."/editar.phtml";


        // Data de Criação
        $dataCriacao = new \DateTime('now');
        $dataCriacao = $dataCriacao->format('d/m/Y');

        // Monta o Conteudo do Controller

        $conteudo = <<<EOD
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">
            Novo(a) $this->tabelaUp(a)
        </div>
    </div>

    <?php
    // objeto form contato
    €form = €this->form;
    // preparar elementos do formulário
    €form->prepare();
    // configurar url formulário
    €form->setAttribute('action', €this->url('$this->modulo-$this->tabela/default', array('controller' => '$this->tabela', 'action' => 'editar', 'id' => €this->id)));

    // renderiza html <form> com atributos configurados no objeto
    echo €this->form()->openTag(€form) ?>
    <div class="panel-body ">
        <div class="container">

EOD;
        foreach($this->dados as $dados):
            // Retira os campos desnecessarios para o formulários
            if(($dados['nomeCampo'] != 'id') && ($dados['nomeCampo'] != 'salt') && ($dados['nomeCampo'] != 'activation_key')
                && ($dados['nomeCampo'] != 'data_cadastro') && ($dados['nomeCampo'] != 'data_alteracao') && ($dados['nomeCampo'] != 'senha')
                && ($dados['nomeCampo'] != 'status')):
                $nomeCampo = $dados['nomeCampo'];
                $nomeCampoUp = ucfirst($nomeCampo);
                $conteudo .= <<<EOD

            <div class='row'>
                <div class='form-group'>
                    <label for='inputTelefonePrincipal' class='col-md-2 control-label label_right'>$nomeCampoUp .:</label>
                    <div class='col-lg-4  col-md-4'>
                        <?php
                        // renderiza html input
                        echo €this->formInput(€form->get('$nomeCampo'));

                        // renderiza elemento de erro
                        echo €this->formElementErrors()
                            ->setMessageOpenFormat("<small class='text-danger'>")
                            ->setMessageSeparatorString("</small><br/><small class='text-danger'>")
                            ->setMessageCloseString("</small>")
                            ->render(€form->get('$nomeCampo'));
                        ?>
                    </div>
                </div>
            </div>

EOD;
            endif;
            if($dados['nomeCampo'] == 'senha'):
                $nomeCampo = $dados['nomeCampo'];
                $nomeCampoUp = ucfirst($nomeCampo);
                $conteudo .= <<<EOD

           <div class='row'>
                <div class='form-group'>
                    <label for='inputTelefonePrincipal' class='col-md-2 control-label label_right'>$nomeCampoUp .:</label>
                    <div class='col-lg-4  col-md-4'>
                        <?php
                        // renderiza html input
                        echo €this->formInput(€form->get('$nomeCampo'));

                        // renderiza elemento de erro
                        echo €this->formElementErrors()
                            ->setMessageOpenFormat("<small class='text-danger'>")
                            ->setMessageSeparatorString("</small><br/><small class='text-danger'>")
                            ->setMessageCloseString("</small>")
                            ->render(€form->get('$nomeCampo'));
                        ?>
                    </div>
                </div>
            </div>

EOD;
            endif;
            if($dados['nomeCampo'] == 'status'):
                $nomeCampo = $dados['nomeCampo'];
                $nomeCampoUp = ucfirst($nomeCampo);
                $conteudo .= <<<EOD

           <div class='row'>
                <div class='form-group'>
                    <label for='inputTelefonePrincipal' class='col-md-2 control-label label_right'>$nomeCampoUp .:</label>
                    <div class='col-lg-4  col-md-4'>
                        <?php
                        // renderiza html input
                        echo €this->formSelect(€form->get('$nomeCampo'));

                        // renderiza elemento de erro
                        echo €this->formElementErrors()
                            ->setMessageOpenFormat("<small class='text-danger'>")
                            ->setMessageSeparatorString("</small><br/><small class='text-danger'>")
                            ->setMessageCloseString("</small>")
                            ->render(€form->get('$nomeCampo'));
                        ?>
                    </div>
                </div>
            </div>

EOD;
            endif;
        endforeach;

        $conteudo .= <<<EOD
        </div>
    </div>

    <div class="panel-footer">
        <button type="submit" id="salvar"  class="btn btn-primary">Salvar $this->tabelaUp(a)</button>
        <a href="<?php echo €this->url('$this->modulo-$this->tabela'); ?>" class="btn btn-default">Voltar</a>
    </div>

    <?php
    // renderiza html
    echo €this->form()->closeTag() ?>
</div>

EOD;
        // Retira o |
        $conteudo = str_replace("|","",$conteudo);
        $conteudo = str_replace("€","$",$conteudo);
        $conteudo = str_replace("º","\"",$conteudo);

        // Se existir o controller e fazer uma copia do controller antigo
        if(!file_exists($nomeEditar)){
            file_put_contents($nomeEditar, $conteudo);
        }
        else {
            copy($nomeEditar,
                $nomeEditar.".old".date("d_m_Y_H:s"));
            file_put_contents($nomeEditar, $conteudo);
        }

//var_dump($nomeEditar);die();
        return "Editar Criado com Sucesso<br>";

    }

}