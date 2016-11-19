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

        $application->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, function(MvcEvent $e) use ($application) {
            /** @var AuthenticationService $authService */
            $authService = $application->getServiceManager()->get('authentication');

            $hasIdentity = $authService->hasIdentity();
            $identity = $authService->getIdentity();

            return;
        }, 100);

        $application->getEventManager()->attach(MvcEvent::EVENT_DISPATCH_ERROR, function(MvcEvent $e) {
            $params = $e->getParams();

            // @TODO better error handling
            if(! (array_key_exists('needsDatabaseUpdate', $params) && $params['needsDatabaseUpdate'] === true)) return;

            $router = $e->getRouter();
            $url = $router->assemble([], ['name' => 'admin/dbtools']);

            /** @var Response $response */
            $response = $e->getResponse();
            $response->setStatusCode(302);
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->sendHeaders();

            return $response;
        });

        $application->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, function(MvcEvent $e) use ($sm) {
            $ctrlPluginManager = $sm->get('ControllerPluginManager');

            /** @var FlashMessenger $flashMessenger */
            $flashMessenger = $ctrlPluginManager->get(FlashMessenger::class);
            $messageContainer = $flashMessenger->getContainer();
            $warningContainer = $messageContainer->offsetGet(FlashMessenger::NAMESPACE_WARNING);

            /** @var Cookie $cookies */
            $cookies = $e->getRequest()->getCookie();
            if(!isset($cookies['warningRemoveQueue'])) return;

            $warningRemoveQueue = $cookies['warningRemoveQueue'];
            $warningRemoveQueue = array_filter(explode(',', $warningRemoveQueue),'strlen');
            $warningRemoveQueue = array_map('intval', $warningRemoveQueue);
            rsort($warningRemoveQueue);

            foreach ($warningRemoveQueue as $messageIndex) {
                if(!isset($warningContainer[$messageIndex])) continue;
                $warningContainer->offsetUnset($messageIndex);
            }

            $unsetCookie = new SetCookie('warningRemoveQueue', '', strtotime('-1 Year'), '/');
            $e->getResponse()->getHeaders()->addHeader($unsetCookie);
        });

        return;
    }

}
