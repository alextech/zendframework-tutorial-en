<?php

namespace ZFT;

use Zend\Db\Adapter\AdapterInterface;
use Zend\EventManager\Event;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\Factory\InvokableFactory;
use ZFT\Authentication\AuthenticationServiceFactory;
use ZFT\Connections\LdapFactory;
use ZFT\Migrations\Migrations;
use ZFT\User\MemoryIdentityMap;
use ZFT\User\PostgresDataMapper;
use ZFT\User\Repository as UserRepository;
use ZFT\User\RepositoryFactory;

class Module implements ServiceProviderInterface {

    public function onBootstrap(MvcEvent $e) {
        $application = $e->getApplication();
        $sm = $application->getServiceManager();

        $em = $application->getEventManager();
        $em->attach(MvcEvent::EVENT_DISPATCH, function(MvcEvent $e) use ($sm, $em) {
            $router = $e->getRouteMatch();
            if(!($router->getParam('needsDatabase') === false)) {
                $adapter = $sm->get('dbcon');

                $migrations = new Migrations($adapter);
                if($migrations->needsUpdate()) {
                    $e->setName(MvcEvent::EVENT_DISPATCH_ERROR);
                    $e->setError('Database Needs Update');
                    $e->setParam('needsDatabaseUpdate', true);

                    $e->stopPropagation(true);
                    $em->triggerEvent($e);

                    return $e->getResult();
                }
            }
        }, 100);
    }

    public function getServiceConfig() {
        return [
            'factories' => [
                PostgresDataMapper::class => InvokableFactory::class,
                MemoryIdentityMap::class => InvokableFactory::class,

                UserRepository::class => RepositoryFactory::class,
                'authentication' => AuthenticationServiceFactory::class,

                'ldap' => LdapFactory::class
            ],
            'aliases' => [
                'dbcon' => AdapterInterface::class
            ]
        ];
    }

}
