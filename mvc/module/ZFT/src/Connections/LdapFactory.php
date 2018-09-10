<?php

namespace ZFT\Connections;

use Interop\Container\ContainerInterface;
use Zend\Ldap\Ldap;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

class LdapFactory implements FactoryInterface {
    /**
     * @param ContainerInterface $sm
     * @param string $requestedName
     * @param array|null|null $options
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @return mixed
     */
    public function __invoke(ContainerInterface $sm, $requestedName, array $options = null) {
        $config = $sm->get('Configuration');

        if(!array_key_exists('ldap', $config)) {
            throw new ServiceNotCreatedException('Configuration is missing "ldap" key');
        }

        $config = $config['ldap'];

        $ldap = new Ldap();
        $ldap->setOptions($config);
        return $ldap;
    }

}
