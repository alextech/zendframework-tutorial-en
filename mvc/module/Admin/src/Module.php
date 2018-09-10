<?php
namespace Admin;

use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventInterface;
use Zend\Http\Header\Cookie;
use Zend\Http\Header\SetCookie;
use Zend\Http\PhpEnvironment\Response;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\ServiceManager\ServiceManager;

class Module implements ConfigProviderInterface, BootstrapListenerInterface {

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e) {
        /** @var MvcEvent $e */
        $application = $e->getApplication();
        $sm = $application->getServiceManager();

        $application->getEventManager()->getSharedManager()->attach(__NAMESPACE__,
            'dispatch', function(MvcEvent $e) {
                $e->getTarget()->layout('layout/admin');
            });

        return;
    }

}
