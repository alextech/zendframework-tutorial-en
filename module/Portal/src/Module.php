<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Portal;

use Portal\Controller\AdminController;
use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventInterface;
use Zend\Http\PhpEnvironment\Response;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;

class Module implements ConfigProviderInterface, BootstrapListenerInterface {
    const VERSION = '3.0.0dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e) {
        /** @var MvcEvent $e */
        $application = $e->getApplication();
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

        return;
    }

}
