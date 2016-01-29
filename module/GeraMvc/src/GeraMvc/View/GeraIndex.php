<?php
/**
 * namespace para nosso modulo GeraMvc\View
 */
namespace GeraMvc\View;

/**
 * class GeraIndex
 * Reponsavel por Gerar Novos Arquivos Index
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package GeraMvc\Controller
 */


class GeraIndex {

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
     * Responsavel por Gera o Index, ele verifica se a existe o index, se exister ele clona o antigo, muda o nome
     * e criar um novo Controller
     * @return string
     */
    public function geraIndex()
    {
        // Verifica se a pasta viw da tabela existe, se nao ele cria ela
        if(!file_exists('./module/'.$this->moduloUp.'/view/'.$this->modulo.'/'.$this->tabela.'/')){
            mkdir('./module/'.$this->moduloUp.'/view/'.$this->modulo.'/'.$this->tabela.'/', 0777, true);
        }

        // Nome do index a ser criado
        $nomeIndex = "./module/".$this->moduloUp."/view/".$this->modulo."/".$this->tabela."/index.phtml";

        // Nome da página de pesquisa
        $nomePesquisa = "./module/".$this->moduloUp."/view/".$this->modulo."/".$this->tabela."/pesquisa.phtml";


        // Data de Criação
        $dataCriacao = new \DateTime('now');
        $dataCriacao = $dataCriacao->format('d/m/Y');
        // Monta o Conteudo do Controller
        $conteudo = <<<EOD
<div class=ºtopo-tableº>
    <a href=º<?php echo €this->url('$this->modulo-$this->tabela/default', array('action' => 'inserir'))?>º class=ºbtn btn-successº title=ºNovoº><span class=ºglyphicon glyphicon-plusº></span></a>
    <form class=ºform-inline pull-rightº role=ºformº  method=ºpostº action=º<?php echo €this->url('$this->modulo-$this->tabela/default', array('action' => 'pesquisa'))?>º>
        <div class=ºform-groupº>
            <label class=ºsr-onlyº for=ºlocalizarº>Buscar...</label>
            <input type=ºsearchº name=ºsearchº value=ºº class=ºform-control typeaheadº id=ºlocalizarº placeholder=ºBucar...º autocomplete=ºoffº>
        </div>
        <button type=ºsubmitº class=ºbtn btn-defaultº><span class=ºglyphicon glyphicon-searchº></span></button>
    </form>
</div>
<br>
<div class=ºpanel panel-primaryº>
    <div class=ºpanel-headingº>
        <div class=ºpanel-titleº>
            Lista $this->tabelaUp(a)
        </div>
    </div>
</div>

<div class=ºcorpo-tableº>
    <table class=ºtable table-striped table-bordered table-hoverº id=ºmyTableº>
        <thead>
        <tr>

EOD;
        foreach($this->dados as $dados):
            $campos = explode('_', $dados['nomeCampo']);
            $campoToUp = ucfirst($campos[0]) . " " . ucfirst($campos[1]);
            // Retira os campos desnecessarios para o formulários
            if(($dados['nomeCampo'] != 'salt') && ($dados['nomeCampo'] != 'activation_key')
                && ($campos[0] != 'data') && ($dados['nomeCampo'] != 'status')
                && ($dados['nomeCampo'] != 'senha')):

                $conteudo .= <<<EOD
            <th>$campoToUp</th>

EOD;
            endif;
            if($campos[0] == 'data'):
                $conteudo .= <<<EOD
            <th>$campoToUp</th>

EOD;
            endif;
            if(($dados['nomeCampo'] == 'status') && ($dados['nomeCampo'] != 'data_alteracao')):
                $conteudo .= <<<EOD
            <th>Status</th>
            <th>Opções</th>

EOD;
            endif;
         endforeach;

        $conteudo .= <<<EOD

        </tr>
        </thead>

        <tbody>
        <?php
        /**
         * @var €entity \Base\Entity\|$this->tabelaUp
         */

        if (€this->data):
            foreach (€this->data as €entity): ?>
                <tr>

EOD;
        foreach($this->dados as $dados):
            // Retira os campos desnecessarios para o formulários
            $campos = explode('_', $dados['nomeCampo']);
            $campo = $campos[0].ucfirst($campos[1]);
            $campoToUp = ucfirst($campos[0]) . ucfirst($campos[1]);

            if(($dados['nomeCampo'] != 'salt') && ($dados['nomeCampo'] != 'activation_key')
                && ($campos[0] != 'data') && ($dados['nomeCampo'] != 'status')
                && ($dados['nomeCampo'] != 'senha')):
                //var_dump($campoToUp);die();
                $conteudo .= <<<EOD
            <td><?php echo €entity->get$campoToUp(); ?></td>

EOD;
            endif;
            if($campos[0] == 'data'):
                $conteudo .= <<<EOD
            <td><?php echo (€entity->get|$campoToUp()) ? €entity->get$campoToUp()->format('d/m/Y') : ""; ?></td>

EOD;
            endif;
            if(($dados['nomeCampo'] == 'status') && ($dados['nomeCampo'] != 'data_alteracao')):
                $conteudo .= <<<EOD
            <td><?php echo (€entity->getStatus() == 1)? 'Ativo' : 'Desativado'; ?></td>

EOD;
            endif;
        endforeach;

        $conteudo .= <<<EOD
                    <td>
                        <button class=ºbtn btn-xs btn-infoº data-toggle=ºmodalº data-target=º#modal-detalhes-$this->tabela|º data-$this->tabela-url=º<?php echo €this->url('$this->modulo-$this->tabela/default', array(ºactionº => ºdetalhesº, ºidº => €entity->getId())); ?>º title=ºVisualizarº ><span class=ºglyphicon glyphicon-new-windowº></span></button>
                        <a class=ºbtn btn-xs btn-warningº title=ºEditarº href=º<?php echo €this->url('$this->modulo-$this->tabela/default', array('action' => 'editar', 'id' => €entity->getId(),))?>º><span class=ºglyphicon glyphicon-editº></span></a>
                        <a class=ºbtn btn-xs btn-dangerº title=ºDeletarº href=º<?php echo €this->url('$this->modulo-$this->tabela/default', array(ºactionº => ºexcluirº, ºidº => €entity->getId(),)); ?>º><span class=ºglyphicon glyphicon-floppy-removeº></span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nem um Registro foi Encontrado</p>
        <?php endif; ?>
        </tbody>
    </table>

    <div class=ºmodal fade fullº id=ºmodal-detalhes-$this->tabela|º>
        <div class=ºmodal-dialogº>
            <div class=ºmodal-contentº>
                <div class=ºmodal-body fullº>

                </div>
            </div>
        </div>
    </div>

    <?php €this->headScript()->captureStart(); ?>
    // Configura a Busca do(a)s $this->tabelaUp
    /**
     * plugin typeahead Busca $this->campoPesquisa $this->tabelaUp
     */
    €(function (){
        €('input.typeahead').typeahead({
            ajax: {
                url: '/app-$this->modulo/$this->tabela/pesquisas',    // url do serviço AJAX
                triggerLength: 2,                   // mínimo de caracteres
                displayField: '$this->campoPesquisa'                // campo do JSON utilizado de retorno
            }
        });
    });
    €(function(){
    // variável para conter a url deletar
    var url_deletar     = '<?php echo €this->url('$this->modulo-$this->tabela/default', array(ºactionº => ºexcluirº)); ?>' + '/';

    // qualquer link que tiver a url deletar vai sofrer um evento quando for clicada
    €(ºa[href*='º + url_deletar + º']º).click(function (event) {
    // variável contendo o id referente ao botão clicado
    var $this->modulo|_id  = €(this).attr('href').split(url_deletar).pop();
    // variável contendo mensagem da janela
    var mensagem = ºDeseja realmente apagar o $this->tabelaUp com ID º + $this->modulo|_id + º?º;
    // variável com resposta da mensagem colocada na janela
    var confirmacao = confirm(mensagem);

    // se a confirmação for false o fluxo é interrompido
    if (!confirmacao)
    // elimar o evendo do botão clicado
    event.preventDefault();
    });
    });

    €(function(){
    €('#modal-detalhes-$this->tabela').on('show.bs.modal', function (event) {
    var button = €(event.relatedTarget)             // Button that triggered the modal
    var $this->tabela|_url = button.data('$this->tabela-url')    // Extract info from data-* attributes

    var modal = €(this)
    modal.
    find('.modal-body').        // localizar corpo modal
    html('Carregando...').      // colocar html caso a requição demore
    load($this->tabela|_url)           // inserir conteudo html AJAX
    })
    });

    // Disable search and ordering by default
    €.extend( €.fn.dataTable.defaults, {
        ºsearchingº: false,


    } );

    €(document).ready(function() {
    €('#myTable').dataTable( {
    ºlanguageº: {
    ºlengthMenuº: ºTotal de _MENU_ Registros por Paginaº,
    ºzeroRecordsº: ºNem um Registro foi Encontradoº,
    ºinfoº: ºMostrando página _PAGE_ de _PAGES_º,
    ºinfoEmptyº: ºNem Registro Encontradoº,
    ºinfoFilteredº: º(filtered from _MAX_ total records)º
    }
    } );
    } );

    <?php €this->headScript()->captureEnd(); ?>

EOD;
        // Retira o |
        $conteudo = str_replace("|","",$conteudo);
        $conteudo = str_replace("€","$",$conteudo);
        $conteudo = str_replace("º","\"",$conteudo);

        // Se existir o controller e fazer uma copia do controller antigo
        if(!file_exists($nomeIndex)){
            file_put_contents($nomeIndex, $conteudo);
        }
        else {
            copy($nomeIndex,
                $nomeIndex.".old".date("d_m_Y_H:s"));
            file_put_contents($nomeIndex, $conteudo);
        }

        // Se existir a página de pesquisa ele fazer uma copia do controller antigo
        if(!file_exists($nomePesquisa)){
            file_put_contents($nomePesquisa, $conteudo);
        }
        else {
            copy($nomePesquisa,
                $nomePesquisa.".old".date("d_m_Y_H:s"));
            file_put_contents($nomePesquisa, $conteudo);
        }

//var_dump($nomeIndex);die();
        return "Index Criado com Sucesso<br>";

    }

}