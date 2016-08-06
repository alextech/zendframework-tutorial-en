<?php

namespace ZFT;

use Interop\Container\ContainerInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use ZFT\User\MemoryIdentityMap;
use ZFT\User\PostgresDataMapper;
use ZFT\User\Repository as UserRepository;

class Module implements ServiceProviderInterface {
    public function getServiceConfig() {
        return [
            'factories' => [
                PostgresDataMapper::class => InvokableFactory::class,
                MemoryIdentityMap::class => InvokableFactory::class,

                UserRepository::class => function(ContainerInterface $serviceManager, $serviceName) {
                    $identityMap = $serviceManager->get(MemoryIdentityMap::class);
                    $dataMapper = $serviceManager->get(PostgresDataMapper::class);

                    return new UserRepository($identityMap, $dataMapper);
                }
            ]
        ];
    }

}
