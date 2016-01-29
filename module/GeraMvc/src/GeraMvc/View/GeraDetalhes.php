<?php
/**
 * namespace para nosso modulo GeraMvc\View
 */
namespace GeraMvc\View;

/**
 * class GeraDetalhes
 * Reponsavel por Gerar Novos Arquivos Detalhes
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package GeraMvc\Controller
 */
class GeraDetalhes {
    // Atributos
    protected $modulo;
    protected $moduloUp;
    protected $tabela;
    protected $tabelaUp;
    protected $dados;

    /**
     * Método __construct($nomeModulo,$nomeTabela,$campoPesquisa)
     * Responsavel por passar os nomes do Modulo, das Tabelas e o Campo de Pesquisa
     * @param $nomeModulo
     * @param $nomeTabela
     */
    public function __construct($nomeModulo,$nomeTabela,$dados)
    {
        $this->tabelaUp = ucfirst($nomeTabela); // Nome da Tabela, a ser gerado o controller, com o primeira letra em Maiuscula
        $this->moduloUp = ucfirst($nomeModulo); // Nome do Modulo aonde o controller sera gerado, com o primeira letra em Maiuscula
        $this->tabela = $nomeTabela; // Nome da Tabela, a ser gerado o controller, em minusculo
        $this->modulo = $nomeModulo; // Nome da Modulo, a ser gerado o controller, em minusculo
        $this->dados = $dados; // Dados para Montar a Listagem dos Dados
    }

    /**
     * Método geraDetalheres()
     * Responsavel por Gera o Inserir, ele verifica se a existe o index, se exister ele clona o antigo, muda o nome
     * e criar um novo Controller
     * @return string
     */
    public function geraDetalheres()
    {
        // Verifica se a pasta viw da tabela existe, se nao ele cria ela
        if(!file_exists('./module/'.$this->moduloUp.'/view/'.$this->modulo.'/'.$this->tabela.'/')){
            mkdir('./module/'.$this->moduloUp.'/view/'.$this->modulo.'/'.$this->tabela.'/', 0777, true);
        }

        // Nome do Detalhes a ser criado
        $nomeDetalhes = "./module/".$this->moduloUp."/view/".$this->modulo."/".$this->tabela."/detalhes.phtml";

        // Data de Criação
        $dataCriacao = new \DateTime('now');
        $dataCriacao = $dataCriacao->format('d/m/Y');

        // Monta o Conteudo do Detalheres
        $conteudo = <<<EOD
<?php
/**
 * @var €entity \Base\Entity\|$this->tabelaUp
 */

foreach(€this->dados as €entity):
    ?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">

            Detalhes do(a) $this->tabelaUp com Cód <?= €entity->getId(); ?>
        </div>
    </div>
    <div class="panel-body">
        <dl class="dl-horizontal">

EOD;
        foreach($this->dados as $dados):
            $campos = explode('_', $dados['nomeCampo']);
            $campoToUp = ucfirst($campos[0]) . " " . ucfirst($campos[1]);
            $campo = ucfirst($campos[0])  . ucfirst($campos[1]);
            // Retira os campos desnecessarios para o formulários
            if(($dados['nomeCampo'] != 'salt') && ($dados['nomeCampo'] != 'activation_key')
                && ($campos[0] != 'data') && ($dados['nomeCampo'] != 'status')
                && ($dados['nomeCampo'] != 'senha')):

                $conteudo .= <<<EOD
            <dt>$campoToUp.: </dt>
                <dd><?= €entity->get$campo(); ?></dd>

EOD;
            endif;
            if($campos[0] == 'data'):
                $conteudo .= <<<EOD
            <dt>$campoToUp</dt>
                <dd><?php echo (€entity->get$campo()) ? €entity->get$campo()->format('d/m/Y') : ""; ?></dd>

EOD;
            endif;
            if(($dados['nomeCampo'] == 'status') ):
                $conteudo .= <<<EOD
            <dt>Status :</dt>
            <dd><?php
                switch(€entity->getStatus()){
                    case 0 : echo 'CANCELADO'; break;
                    case 1 : echo 'ATIVO'; break;
                }
                ?></dd>

EOD;
            endif;


        endforeach;
        $conteudo .= <<<EOD

        </dl>
    <div>
    <div class="panel-footer">
            <a href="<?php echo €this->url('$this->modulo-$this->tabela/default', array('action' => 'editar', 'id' => €entity->getId())); ?>" class="btn btn-warning btn-sm">Editar $this->tabelaUp</a>
            <a href="<?php echo €this->url('$this->modulo-$this->tabela/default'); ?>" class="btn btn-default btn-sm">Voltar</a>
        </div>
</div>
<?php
endforeach; ?>

EOD;
        // Retira o |
        $conteudo = str_replace("|","",$conteudo);
        $conteudo = str_replace("€","$",$conteudo);
        $conteudo = str_replace("º","\"",$conteudo);

        // Se existir o Detalhere e fazer uma copia do Detalhere antigo
        if(!file_exists($nomeDetalhes)){
            file_put_contents($nomeDetalhes, $conteudo);
        }
        else {
            copy($nomeDetalhes,
                $nomeDetalhes.".old".date("d_m_Y_H:s"));
            file_put_contents($nomeDetalhes, $conteudo);
        }

        return "Detalhere Criado com Sucesso<br>";

    }
}