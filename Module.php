<?php

namespace AlbumRest;

use Zend\ModuleManager\Feature;         // <-- Add this import
use Zend\EventManager\EventInterface;   // <-- Add this import
use Zend\Mvc\MvcEvent;                  // <-- Add this import

class Module implements
    Feature\BootstrapListenerInterface // Add this Interface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    // Add this Method
    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getApplication();
        $em  = $app->getEventManager()->getSharedManager();
        $sm  = $app->getServiceManager();

        $em->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function($e) use ($sm) {
            $strategy = $sm->get('ViewJsonStrategy');
            $view     = $sm->get('ViewManager')->getView();
            $strategy->attach($view->getEventManager());
        });
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}