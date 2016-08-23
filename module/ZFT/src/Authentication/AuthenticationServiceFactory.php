<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 23.08.2016
 * Time: 0:29
 */

namespace ZFT\Authentication;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Authentication\Adapter\Ldap as LdapAdapter;

class AuthenticationServiceFactory implements FactoryInterface {

    /**
     * @param ContainerInterface $sm
     * @param string $requestedName
     * @param array|null|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $sm, $requestedName, array $options = null) {
        $config = $sm->get('Configuration');

        $adapter = new LdapAdapter();
        $adapter->setOptions($config['ldapServers']);

        $auth = new AuthenticationService();
        $auth->setAdapter($adapter);

        return $auth;
    }


}