<?php

namespace ZFT\User;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZFT\User\Repository as UserRepository;

class RepositoryFactory implements FactoryInterface {
    public function __invoke(ContainerInterface $serviceManager, $requestedName, array $options = null) {

        return new UserRepository(
            new TableGateway(
                'users',
                $serviceManager->get('dbcon'),
                [],
                new HydratingResultSet(new ClassMethods(true), new User())
            ));
    }

}
