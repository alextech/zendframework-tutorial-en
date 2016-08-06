<?php

namespace Portal\Controller;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

use ZFT\User;

class UserRelatedControllerFactory implements FactoryInterface {
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
        if(!class_exists($controllerName)) {
            throw new ServiceNotFoundException("Requested controller name '".$controllerName."' does not exist.");
        }

        $repository = $serviceManager->get(User\Repository::class);

        return new $controllerName($repository);
    }


}
