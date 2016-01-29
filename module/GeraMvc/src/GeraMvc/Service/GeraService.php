<?php
/**
 * namespace para nosso modulo Base\Service
 */
namespace GeraMvc\Service;

/**
 * class GeraService
 * Reponsavel por Gerar Novo Service
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package GeraMvc\Controller
 */
class GeraService {

    private $moduloUp;
    private $nomeTabelaUp;

    public function __construct($nomeModulo,$nomeTabela)
    {
        $this->moduloUp = ucfirst($nomeModulo); // Nome do Modulo aonde o Formulário sera gerado, com o primeira letra em Maiuscula
        $this->nomeTabelaUp = ucfirst($nomeTabela); // Nome da Tabela Com a primeira Leita em Maiuscula
    }

    public function GeraService()
    {
        // Verifica se a pasta Service existe, se nao ele cria ela
        if(!file_exists('./module/'.$this->moduloUp.'/src/'.$this->moduloUp.'/Service')){
            mkdir('./module/'.$this->moduloUp.'/src/'.$this->moduloUp.'/Service', 0777, true);
        }

        // Nome do Service
        $nomeService = "./module/".$this->moduloUp."/src/".$this->moduloUp."/Service/".$this->nomeTabelaUp."Service.php";

        // Data Criação
        $dataCriacao = new \DateTime('now');
        $dataCriacao = $dataCriacao->format('d/m/Y');

        // Monta o Filter
        $conteudo = <<<EOD
<?php
/**
 * namespace para nosso modulo $this->moduloUp\Service
 */

namespace $this->moduloUp\Service;
use Base\Service\AbstractService;
use Doctrine\ORM\EntityManager;

/**
 * class $this->nomeTabelaUp|Service
 * Responsavel por gerenciar as movimentações na entidade $this->nomeTabelaUp
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package Admin\Service
 * Data Criação $dataCriacao
 */

class $this->nomeTabelaUp|Service extends AbstractService
{
    public function __construct(EntityManager €em)
    {
        €this->entity = 'Admin\Entity\|$this->nomeTabelaUp';
        parent::__construct(€em);
    }

}

EOD;
        // Retira o |
        $conteudo = str_replace("|","",$conteudo);
        $conteudo = str_replace("€","$",$conteudo);

        // Se existir o controller e fazer uma copia do controller antigo
        if(!file_exists($nomeService)){
            file_put_contents($nomeService, $conteudo);
        }
        else {
            copy($nomeService,
                $nomeService.".old".date("d_m_Y_H:s"));
            file_put_contents($nomeService, $conteudo);
        }

        return "Serviço Criado com Sucesso<br>";


    }

}