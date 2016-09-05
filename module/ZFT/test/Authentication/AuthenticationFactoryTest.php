<?php
namespace ZFTest\Authentication;


use Zend\Authentication;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceManager;
use Zend\Session;
use ZFT\Authentication\AuthenticationServiceFactory;
use ZFT\Connections\LdapFactory;

class AuthenticationFactoryTest extends \PHPUnit_Framework_TestCase {

    /** @var  ServiceManager */
    private $sm;

    public function setUp() {
        parent::setUp();

        $this->sm = new ServiceManager();
        $this->sm->setService('Configuration', require __DIR__.'/../../../../config/autoload/global.php');
        $this->sm->setService('authentication', new AuthenticationServiceFactory());
        $this->sm->setFactory('ldap', LdapFactory::class);
    }

    public function testCanCreateAuthenticationService() {
        $authServiceFactory = new AuthenticationServiceFactory();

        /** @var AuthenticationService $authService */
        $authService = $authServiceFactory($this->sm, 'authentication');

        $this->assertInstanceOf(AuthenticationService::class, $authService);
        $this->assertInstanceOf(AdapterInterface::class, $authService->getAdapter());

        $rs = $authService->authenticate();

        return;
    }

    public function testIdentityCreated() {
        /** @var AuthenticationServiceFactory $authServiceFactory */
        $authServiceFactory = $this->sm->get('authentication');

        /** @var AuthenticationService $auth */
        $auth = $authServiceFactory($this->sm, 'authentication');
        $auth->clearIdentity();

        /** @var Authentication\Adapter\Ldap $adapter */
        $adapter = $auth->getAdapter();
        $adapter->setIdentity('zftutorial');
        $adapter->setPassword('ZFT2016!');

        $auth->authenticate();

        $container = new Session\Container(Authentication\Storage\Session::NAMESPACE_DEFAULT);
        $identity = $container->{Authentication\Storage\Session::MEMBER_DEFAULT};

        $this->assertEquals('alex-tech\\zftutorial', $identity);

        return;
    }

}
