<?php
/**
 * namespace para nosso modulo Base\Controller
 */
namespace Base\Controller;

use Base\Email\EnviarEmail;
use Doctrine\Common\Util\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;

/**
 * class AbstractController
 * Responsável por gerencia os controller
 *
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 */


abstract class AbstractController extends AbstractActionController
{
    /**
     * @var
     */
    protected $em; // EntityManager
    /**
     * @var
     */
    protected $entity; // Entidade
    /**
     * @var
     */
    protected $controller; // Controller
    /**
     * @var
     */
    protected $route; // Rota
    /**
     * @var
     */
    protected $service; // Serviço
    /**
     * @var
     */
    protected $form; // Formulário

    protected $itemPorPaigina = 30;

    protected $campoPesquisa = null;
    protected $dadoPesquisa = null;
    protected $campoOrder = null;
    protected $order = null;
    protected $authService;

    // Atributos para enviar Emails
    protected $enviar = 0;   // Se for 1 ele enviar o email
    protected $rementente;   // Quem esta Enviando
    protected $titulo;       // Titulo do Email
    protected $assunto;      // Assunto do Email

    abstract function __construct();

    /**
     * Método Lista Resultados
     * Responsavel por listar todos os resultados, na pagina index, por padrão irar mostrar 30 resultados
     * por páginas, também esta usando paginador.
     *
     * @return array|ViewModel
     */
    public function indexAction()
    {
        // Resebe os dados da Entidade passada
        if($this->campoPesquisa):
            $list = $this->getEm()->getRepository($this->entity)->findBy(array($this->campoPesquisa => $this->dadoPesquisa),array($this->campoOrder => $this->order));
        else:
            $list = $this->getEm()->getRepository($this->entity)->findAll();
        endif;

        // Pega o numero da pagina
        $page = $this->params()->fromRoute('page');

        // Cria a instancia do Paginador
        $paginator = new Paginator(new ArrayAdapter($list));
        $paginator->setCurrentPageNumber($page)
            ->setDefaultItemCountPerPage($this->itemPorPaigina);

        // Verifica se foi retornado alguma mensagem de Sucessos
        if ($this->flashMessenger()->hasSuccessMessages()){
            return new ViewModel(array(
                'data' => $paginator, 'page' => $page,
                'success' => $this->flashMessenger()->getSuccessMessages()));
        }

        // Verifica se foi retornado alguma mensagem de Error
        if ($this->flashMessenger()->hasErrorMessages()){
            return new ViewModel(array(
                'data' => $paginator, 'page' => $page,
                'error' => $this->flashMessenger()->getErrorMessages()));
        }


        // Passa os dados para a View
        return new ViewModel(array('data' => $list, 'page' => $page));
    }

    /**
     * Método Inserir Registro
     * Resposavel por realizar as inclusões nas entidades
     * @return \Zend\Http\Response|ViewModel
     */
    public function inserirAction()
    {
        // Verifica se foi passado um objeto Form, senão ele cria um objeto Form
        //$this->form = $this->getServiceLocator()->get($this->form);

        // Verifica se foi passado um objeto Form, senão ele cria um objeto Form
        if (is_string($this->form))
            $form = new $this->form;
        else
            $form = $this->form;

        // Recebe os dados vendo pela Request(POST,GET)
        $request = $this->getRequest();

        // Verifica se o Request veio pelo método Post
        if ($request->isPost()){
            // Recebe tudo que foi enviado POST, GET, FILE
            $postData = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );


            // Passa os dados vindo pelo post para o Form
            $form->setData($postData);

            // Verifica se o Formulario e Valido
            if ($form->isValid()){


                // Pega a Instancia do serviço "Entidade"
                $service = $this->getServiceLocator()->get($this->service);
                $datas = $form->getData();

                if(!empty($datas['arquivo'])):
                    $arquivo = array_filter(explode(DIRECTORY_SEPARATOR, $datas['arquivo']['tmp_name']));
                    $datas['arquivo'] = array_pop($arquivo);//Nome do arquivo randômico
                endif;

                if ($service->save($datas)){

                    $this->flashMessenger()->addSuccessMessage('Cadastrado com sucesso!');
                }else{
                    $this->flashMessenger()->addErrorMessage('Não foi possivel cadastrar! Tente mais tarde.');
                }

                // Redireciona para a index do Controller
                return $this->redirect()
                    ->toRoute($this->route, array('controller' => $this->controller,));
            }

        }

        // Instancia o formulario na view
        return new ViewModel(array('form' => $form));
    }

    /**
     * Método Editar
     * Responsavel por realizar as Alterações nas Entidade
     * @return \Zend\Http\Response|ViewModel
     */
    public function editarAction()
    {
        // Verifica se foi passado um objeto Form, senão ele cria um objeto Form
        if (is_string($this->form))
            $form = new $this->form;
        else
            $form = $this->form;

        // Recebe os dados vendo pela Request(POST,GET)
        $request = $this->getRequest();
        // Recebe o Id do Registro
        $param = $this->params()->fromRoute('id', 0);

        // Retorna os dados da entidade atravez da pesquisa do Id
        $repository = $this->getEm()->getRepository($this->entity)->find($param);

        // Verifica se foi retornado dados validos da pesquisa
        if ($repository){

            $array = array();
            foreach($repository->toArray() as $key => $value){
                // Verifica se algum dos dados e do tipo DateTime
                if ($value instanceof \DateTime)
                    $array[$key] = $value->format('d/m/Y');
                else
                    $array[$key] = $value;
            }

            // Passa os dados para o Formulario
            $form->setData($array);

            // Verifica se os dados vieram atravez do Post
            if ($request->isPost()){
                // Recebe tudo que foi enviado POST, GET, FILE
                $postData = array_merge_recursive(
                    $this->getRequest()->getPost()->toArray(),
                    $this->getRequest()->getFiles()->toArray()
                );


                // Passa os dados para o Formulario
                $form->setData($postData);


                // Verifica o formulario
                if ($form->isValid()){
                    //var_dump($form);die("AbastractController L 191");

                    // Pega a Instancia do Serviço "Entidade"
                    $service = $this->getServiceLocator()->get($this->service);

                    // Passa os dados para a variavel $data
                    $data = $form->getData();

                    // Passa o Id
                    $data['id'] = (int) $param;

                    // Verifica se não foi passado a senha, e retira a senha para atualizar
                    if(empty($data['senha'])){
                        unset($data['senha']);
                    }
                    // Verifica se a arquivo vai ser trocada, se for ela excluir a arquivo e upa a nova
                    if(!empty($data['arquivo'])):

                        // Retorna os dados da entidade atravez da pesquisa do Id
                        $repository = $this->getEm()->getRepository($this->entity)->find($data['id']);
                        // Debugar
                        //Debug::dump($repository->toArray()['arquivo']);die();
                        // Excluir o arquivo
                        $destinho = "./public_html/midias/".strtolower($this->controller).'/'.$repository->toArray()['arquivo'];
                        //var_dump($destinho);die();
                        unlink($destinho);


                        $arquivo = array_filter(explode(DIRECTORY_SEPARATOR, $data['arquivo']['tmp_name']));
                        $data['arquivo'] = array_pop($arquivo);//Nome do arquivo randômico
                    endif;

                    // Verifica se foi auterado os dados com sucesso na entidade
                    if ($service->save($data)){
                        $this->flashMessenger()->addSuccessMessage('Atualizado com sucesso!');
                    }else{
                        $this->flashMessenger()->addErrorMessage('Não foi possivel atualizar! Tente mais tarde.');
                    }

                    // Redireciona para o Página Editar do Controller, com os Dados ja Auterados
                    return $this->redirect()
                        ->toRoute($this->route,
                            array('controller' => $this->controller,
                                'action' => 'editar', 'id' => $param));
                }

            }

        }else{
            $this->flashMessenger()->addInfoMessage('Registro não foi encontrado!');
            return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
        }

        // Verifica se foi retornado alguma mensagem de Sucessos
        if ($this->flashMessenger()->hasSuccessMessages()){
            return new ViewModel(array(
                'form' => $form,
                'success' => $this->flashMessenger()->getSuccessMessages(),
                'id' => $param
            ));
        }

        // Verifica se foi retornado alguma mensagem de Error
        if ($this->flashMessenger()->hasErrorMessages()){
            return new ViewModel(array(
                'form' => $form,
                'error' => $this->flashMessenger()->getErrorMessages(),
                'id' => $param
            ));
        }

        // Verifica se foi retornado alguma mensagem de Informações
        if ($this->flashMessenger()->hasInfoMessages()){
            return new ViewModel(array(
                'form' => $form,
                'warning' => $this->flashMessenger()->getInfoMessages(),
                'id' => $param
            ));
        }

        // Limpa as Mensagens
        $this->flashMessenger()->clearMessages();

        // Instancia o Formulario na View
        return new ViewModel(array('form' => $form, 'id' => $param));
    }

    /**
     * Método Excluir
     * Responsavel por Excluir um Registro da Entidade.
     * Atravez do Id passado
     * @return \Zend\Http\Response
     */
        public function excluirAction()
        {
            // Recebe o serviço "Entidade"
            $service = $this->getServiceLocator()->get($this->service);
            // Recebe o Id
            $data['id'] = $this->params()->fromRoute('id', 0);
            $data['status'] = '0';


            // Verifica de ocorreu a Remoção com Sucesso
            if ($service->save($data))
                $this->flashMessenger()->addSuccessMessage('Resistro deletado com sucesso!');
            else
                $this->flashMessenger()->addErrorMessage('Não foi possivel deletar o registro!');

            // Retorno para a Página Index do Controller
            return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
        }

    /**
     * Método getEm
     * Responsavel por gerenciar as interações das entidades com o banco atravez do Doctrine
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        if ($this->em == null){
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->em;
    }

    public function getDados(){
        $modulo = $this->getEvent()->getTarget();
        $controllerClass = get_class($modulo);
        $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));

        $sessionStorage = new SessionStorage($moduleNamespace);
        $se = new AuthenticationService;
        $se->setStorage($sessionStorage);
        return $se->getIdentity();

    }

}