<?php
namespace ZFTest\Authentication;


use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\StorageInterface;
use Zend\ServiceManager\ServiceManager;
use ZFT\Authentication\AuthenticationServiceFactory;

class AuthenticationFactoryTest extends \PHPUnit_Framework_TestCase {

    /** @var  ServiceManager */
    private $sm;

    public function setUp() {
        parent::setUp();

        $this->sm = new ServiceManager();
        $this->sm->setService('Configuration', require __DIR__.'/../../../../config/autoload/global.php');
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

}
