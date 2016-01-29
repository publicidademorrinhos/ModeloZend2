<?php

namespace Base;

use Base\Auth\Adapter;
use Base\Plugin\Cache;
use Base\View\BannerHelp;
use Base\View\MenuAtivoView;
use Base\View\MessageView;
use Base\View\UserIdentity;
use Base\View\ViewHead;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $sm = $e->getApplication()->getServiceManager();

        //Aqui eu declaro o Helper Manager
        $sm->get('viewhelpermanager')->setFactory('BannerHelp', function ($sm) use ($e) {
            return new BannerHelp($e, $sm);
        });

        //Aqui eu declaro o Helper Manager
        $sm->get('viewhelpermanager')->setFactory('ViewHead', function ($sm) use ($e) {
            return new ViewHead($e, $sm);
        });


    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Register View Helper
     */
    public function getViewHelperConfig()
    {
        return array(
            # registrar View Helper com injecao de dependecia
            'factories' => array(
                'menuAtivo'  => function($sm) {
                        return new MenuAtivoView($sm->getServiceLocator()->get('Request'));
                    },
                'message' => function($sm) {
                        return new MessageView($sm->getServiceLocator()->get('ControllerPluginManager')->get('flashmessenger'));
                    },
            ),
            'invokables' => array(
                'UserIdentity' => new UserIdentity(),
                'viewHead' => 'Base\View\ViewHead',
                'bannerHelp' => 'Base\View\BannerHelp',
            ),
        );

    }

    /**
     * Register Controller Plugin
     */
    public function getControllerPluginConfig()
    {
        return array(
            'factories' => array(
                'cache' => function($sm) {
                    return new Cache($sm->getServiceLocator()->get('Cache\FileSystem'));
                },
            ),
        );
    }

    /**
     * Resitar os EntityManager dos Serviços
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Base\Auth\Adapter' => function($em){
                    return new Adapter($em->get('Doctrine\ORM\EntityManager'));
                },
            ),
        );
    }
}