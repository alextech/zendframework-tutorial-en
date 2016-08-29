<?php

namespace ZFT;

use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use ZFT\Authentication\AuthenticationServiceFactory;
use ZFT\Connections\LdapFactory;
use ZFT\User\MemoryIdentityMap;
use ZFT\User\PostgresDataMapper;
use ZFT\User\Repository as UserRepository;
use ZFT\User\RepositoryFactory;

class Module implements ServiceProviderInterface {
    public function getServiceConfig() {
        return [
            'factories' => [
                PostgresDataMapper::class => InvokableFactory::class,
                MemoryIdentityMap::class => InvokableFactory::class,

                UserRepository::class => RepositoryFactory::class,
                'authentication' => AuthenticationServiceFactory::class,

                'ldap' => LdapFactory::class
            ]
        ];
    }

}
