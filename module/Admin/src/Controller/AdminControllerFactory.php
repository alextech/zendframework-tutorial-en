<?php

namespace Admin\Controller;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Db\Adapter\Platform\Postgresql;
use Zend\Db\Adapter\Platform\SqlServer;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

use ZFT\Migrations\Migrations;
use ZFT\Migrations\Platform\SqlServerMigrations;
use ZFT\User;

class AdminControllerFactory implements FactoryInterface {
    /**
     * @param ContainerInterface $serviceManager
     * @param string $controllerName
     * @param array|null|null $options
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when creating a service.
     * @throws ContainerException if any other error occurs
     * @return mixed
     */
    public function __invoke(ContainerInterface $serviceManager, $controllerName, array $options = null) {
        $dbcon = $serviceManager->get('dbcon');
        $platform = $dbcon->platform;

        switch (true) {
            case $platform instanceof SqlServer:
                $migrations = new SqlServerMigrations($dbcon);
                break;
            case $platform instanceof Postgresql:
                $migrations = new Migrations($dbcon);
                break;
        }

        return new AdminController($migrations);
    }


}
